<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Pipeline;
use App\Models\PipelineStage;
use App\Models\Deal;
use App\Models\Product;
use App\Repositories\Contracts\DealRepositoryInterface;
use App\Actions\CRM\UpdateDealStageAction;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;

class DealKanbanBoard extends Component
{
    public $pipelineId;
    
    #[Url]
    public $highlightDeal = null;

    public $stages = [];
    public $deals = [];
    public $listDeals = [];
    public $activityDeals = [];
    public $currentView = 'kanban';

    public $isCreatingDeal = false;
    public $newDealTitle = '';
    public $newDealAmount = 0;
    public $newDealStartDate = '';
    public $newDealEndDate = '';
    public $newDealClientName = '';
    public $newDealClientPhone = '';
    public $newDealType = 'regular';
    public $newDealSource = 'other';
    public $newDealSourceInfo = '';
    public $newDealDescription = '';
    public $newDealAvailableToEveryone = false;
    
    // Multi-assignment
    public $newDealAssignedUsers = [];
    public $newDealAssignedRoles = [];
    public $newDealAssignedTeams = [];

    // Details Slide-over
    public $isViewingDeal = false;
    public $selectedDeal = null;

    // Quick Task
    public $quickTaskTitle = '';
    public $quickTaskDescription = '';
    public $quickTaskDueDate = '';
    public $quickTaskPriority = 'medium';

    public $dealProducts = []; // array of ['product_id', 'name', 'price', 'quantity']
    public $productTab = 'select'; // 'select' or 'create'
    public $newProductName = '';
    public $newProductUnit = 'dona';

    protected $listeners = [
        'productSelected' => 'addProductFromSearch'
    ];

    #[Computed]
    public function allProducts()
    {
        return \App\Models\Product::all();
    }

    #[Computed]
    public function allUsers()
    {
        $user = auth()->user();
        if ($user->hasRole('Admin')) {
            return \App\Models\User::where('status', 'active')->get();
        } elseif ($user->managerOf) {
            return \App\Models\User::where('status', 'active')
                ->where(function($q) use ($user) {
                    $q->where('id', $user->id)
                      ->orWhere('team_id', $user->managerOf->id);
                })->get();
        } else {
            return \App\Models\User::where('id', $user->id)->get();
        }
    }

    public function addProductFromList($productId)
    {
        $product = \App\Models\Product::find($productId);
        if ($product) {
            $this->dealProducts[] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'unit' => $product->unit,
                'quantity' => 1
            ];
        }
    }

    public function addProductFromSearch($id, $name, $eventName)
    {
        if ($eventName === 'productSelected' && $id) {
            // check if already added
            foreach ($this->dealProducts as $p) {
                if ($p['product_id'] == $id) return;
            }
            $product = Product::find($id);
            if ($product) {
                $this->dealProducts[] = [
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'unit' => $product->unit,
                    'price' => $product->price,
                    'quantity' => 1
                ];
            }
        }
    }

    public function createAndAddProduct()
    {
        $this->validate([
            'newProductName' => 'required|string',
            'newProductUnit' => 'required|string',
        ]);

        $product = Product::create([
            'name' => $this->newProductName,
            'unit' => $this->newProductUnit,
            'price' => 0,
        ]);

        $this->dealProducts[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'unit' => $product->unit,
            'quantity' => 1
        ];

        $this->newProductName = '';
        $this->newProductUnit = 'dona';
    }

    public function removeProduct($index)
    {
        if (isset($this->dealProducts[$index])) {
            unset($this->dealProducts[$index]);
            $this->dealProducts = array_values($this->dealProducts);
        }
    }

    public function deleteProductFromDb($productId)
    {
        // DB dan o'chirish
        Product::destroy($productId);
        
        // Agar biriktirilganlar orasida bo'lsa, ularni ham ro'yxatdan olib tashlash
        $this->dealProducts = array_values(array_filter($this->dealProducts, function($p) use ($productId) {
            return $p['product_id'] != $productId;
        }));
    }

    public function mount($pipelineId = null)
    {
        $this->pipelineId = $pipelineId ?? Pipeline::first()?->id;
        
        // If no pipeline exists, create a default one with default stages
        if (!$this->pipelineId) {
            $pipeline = Pipeline::create(['name' => 'Asosiy Voronka']);
            $this->pipelineId = $pipeline->id;
            
            // Create default stages
            $stages = ['Yangi bitim', 'Muzokara', 'Shartnoma', 'Yopilgan'];
            foreach ($stages as $index => $stageName) {
                PipelineStage::create([
                    'pipeline_id' => $pipeline->id,
                    'name' => $stageName,
                    'order' => $index + 1
                ]);
            }
        }
        
        $this->loadData();
    }

    public function setView($view)
    {
        if (in_array($view, ['kanban', 'list', 'activities'])) {
            $this->currentView = $view;
            $this->loadData();
        }
    }

    public function loadData()
    {
        if (!$this->pipelineId) return;

        $this->stages = PipelineStage::where('pipeline_id', $this->pipelineId)
            ->orderBy('order')
            ->get();

        $query = Deal::with(['contact', 'assignee', 'tasks'])
            ->whereIn('pipeline_stage_id', $this->stages->pluck('id'));

        if (!auth()->user()->hasRole('Admin')) {
            // Need a more complex query for JSON overlapping
            // Or just fetch all and filter using the model's hasAccess
        }

        $allDeals = $query->get()->filter(function($deal) {
            return $deal->hasAccess(auth()->user());
        })->values();
        
        if ($this->currentView === 'kanban') {
            $this->deals = [];
            foreach ($this->stages as $stage) {
                $this->deals[$stage->id] = $allDeals->where('pipeline_stage_id', $stage->id)->values();
            }
        } elseif ($this->currentView === 'list') {
            $this->listDeals = $allDeals;
        } elseif ($this->currentView === 'activities') {
            $this->processActivitiesView($allDeals);
        }
    }

    protected function processActivitiesView($allDeals)
    {
        $today = now()->startOfDay();
        $endOfWeek = now()->endOfWeek();
        $startOfNextWeek = now()->addWeek()->startOfWeek();
        $endOfNextWeek = now()->addWeek()->endOfWeek();

        $this->activityDeals = [
            'overdue' => [],
            'today' => [],
            'this_week' => [],
            'next_week' => [],
            'idle' => [],
            'later' => []
        ];

        foreach ($allDeals as $deal) {
            // Only consider tasks that are not completed (assuming status != 'completed')
            $pendingTasks = $deal->tasks->where('status', '!=', 'completed');
            
            if ($pendingTasks->isEmpty()) {
                $this->activityDeals['idle'][] = $deal;
                continue;
            }

            // Find the most urgent task
            $earliestTask = $pendingTasks->sortBy('due_date')->first();
            $dueDate = $earliestTask->due_date ? \Carbon\Carbon::parse($earliestTask->due_date)->startOfDay() : null;

            if (!$dueDate) {
                $this->activityDeals['idle'][] = $deal;
            } elseif ($dueDate->lt($today)) {
                $this->activityDeals['overdue'][] = $deal;
            } elseif ($dueDate->eq($today)) {
                $this->activityDeals['today'][] = $deal;
            } elseif ($dueDate->lte($endOfWeek)) {
                $this->activityDeals['this_week'][] = $deal;
            } elseif ($dueDate->between($startOfNextWeek, $endOfNextWeek)) {
                $this->activityDeals['next_week'][] = $deal;
            } else {
                $this->activityDeals['later'][] = $deal;
            }
        }
    }

    public function updateDealStage($dealId, $newStageId, UpdateDealStageAction $updateAction)
    {
        $updateAction->execute($dealId, $newStageId);
        $this->loadData();
    }

    public function openCreateModal()
    {
        $this->reset([
            'newDealTitle', 'newDealAmount', 'newDealStartDate', 'newDealEndDate',
            'newDealClientName', 'newDealClientPhone', 'newDealType', 'newDealSource',
            'newDealSourceInfo', 'newDealDescription', 'newDealAvailableToEveryone',
            'dealProducts', 'productTab', 'newProductName', 'newProductUnit',
            'newDealAssignedUsers', 'newDealAssignedRoles', 'newDealAssignedTeams'
        ]);
        $this->isCreatingDeal = true;
    }

    public function saveDeal()
    {
        $this->validate([
            'newDealTitle' => 'required|string|max:255',
            'newDealAmount' => 'nullable|numeric|min:0',
            'newDealClientName' => 'required|string|max:255',
            'newDealClientPhone' => 'required|string|max:50',
        ]);

        $amountInUsd = (float)($this->newDealAmount ?: 0);

        // Create or find contact
        $contact = \App\Models\Contact::firstOrCreate(
            ['phone' => $this->newDealClientPhone],
            ['name' => $this->newDealClientName]
        );

        $firstStage = \App\Models\PipelineStage::where('pipeline_id', $this->pipelineId)->orderBy('order')->first();
        if ($firstStage) {
            $startTime = $this->newDealStartDate ? \Carbon\Carbon::parse($this->newDealStartDate) : now();
            if ($startTime->isPast() && !$this->newDealStartDate) {
                $startTime = now();
            }

            $deal = Deal::create([
                'title' => $this->newDealTitle,
                'amount' => $amountInUsd,
                'pipeline_stage_id' => $firstStage->id,
                'start_date' => $startTime,
                'end_date' => $this->newDealEndDate ? \Carbon\Carbon::parse($this->newDealEndDate) : null,
                'contact_id' => $contact->id,
                'company_id' => null,
                'deal_type' => $this->newDealType,
                'source' => $this->newDealSource,
                'source_info' => $this->newDealSourceInfo,
                'description' => $this->newDealDescription,
                'available_to_everyone' => $this->newDealAvailableToEveryone,
                'assigned_users' => empty($this->newDealAssignedUsers) && empty($this->newDealAssignedRoles) && empty($this->newDealAssignedTeams) ? [auth()->id()] : $this->newDealAssignedUsers,
                'assigned_roles' => $this->newDealAssignedRoles,
                'assigned_teams' => $this->newDealAssignedTeams,
            ]);

            // Sync products
            $syncData = [];
            foreach ($this->dealProducts as $p) {
                $syncData[$p['product_id']] = [
                    'quantity' => $p['quantity'],
                    'price' => $p['price']
                ];
            }
            $deal->products()->sync($syncData);
        }
        
        $this->isCreatingDeal = false;
        $this->loadData();
    }

    public function deleteDeal($dealId)
    {
        Deal::destroy($dealId);
        $this->loadData();
    }

    public function viewDeal($dealId)
    {
        $this->selectedDeal = Deal::with(['contact', 'tasks', 'products'])->find($dealId);
        if ($this->selectedDeal && $this->selectedDeal->hasAccess(auth()->user())) {
            $this->isViewingDeal = true;
        }
    }

    public function closeDealView()
    {
        $this->isViewingDeal = false;
        $this->selectedDeal = null;
        $this->loadData(); // refresh in case it was edited
    }

    public function saveQuickTask(\App\Actions\Project\CreateTaskAction $action)
    {
        if (!$this->selectedDeal) return;

        // Check if user is assigned or has manager/admin permissions
        $assignedUsers = $this->selectedDeal->assigned_users ?? [];
        $hasAccess = in_array(auth()->id(), $assignedUsers) 
            || auth()->user()->hasRole('Admin') 
            || auth()->user()->can('assign_deal') 
            || auth()->user()->managerOf;
            
        if (!$hasAccess) return;

        $this->validate([
            'quickTaskTitle' => 'required|string|max:255',
            'quickTaskPriority' => 'required|in:low,medium,high',
        ], [
            'quickTaskTitle.required' => __('Vazifa nomi kiritilishi shart.'),
            'quickTaskPriority.required' => __('Ustuvorlik tanlanishi shart.'),
        ]);

        $dto = new \App\DTOs\Project\TaskData(
            title: $this->quickTaskTitle,
            deal_id: $this->selectedDeal->id,
            description: $this->quickTaskDescription,
            status: 'not_planned',
            priority: $this->quickTaskPriority,
            start_date: now()->format('Y-m-d H:i:s'),
            due_date: $this->quickTaskDueDate ?: now()->addDay()->format('Y-m-d H:i:s'),
            assigned_to: [auth()->id()],
            assigned_to_department_id: null,
            assigned_to_everyone: false,
            created_by: auth()->id(),
            observers: [],
            available_to_everyone: false
        );

        $action->execute($dto);
        
        $this->reset(['quickTaskTitle', 'quickTaskDescription', 'quickTaskDueDate']);
        $this->quickTaskPriority = 'medium';
        $this->selectedDeal->refresh();
    }

    public function updateDealAssignments($dealId, $users, $roles, $teams)
    {
        if (auth()->user()->hasRole('Admin') || auth()->user()->can('assign_deal') || auth()->user()->managerOf) {
            $deal = Deal::find($dealId);
            if ($deal) {
                $deal->update([
                    'assigned_users' => $users,
                    'assigned_roles' => $roles,
                    'assigned_teams' => $teams,
                ]);
                
                if ($this->selectedDeal && $this->selectedDeal->id === $dealId) {
                    $this->selectedDeal->refresh();
                }
                
                $this->loadData();
            }
        }
    }

    public function render()
    {
        return view('livewire.deal-kanban-board')->layout('layouts.app');
    }
}
