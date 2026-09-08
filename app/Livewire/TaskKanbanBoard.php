<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\Project;
use App\Actions\Project\ChangeTaskStatusAction;
use App\Actions\Project\CreateTaskAction;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\DTOs\Project\TaskData;

class TaskKanbanBoard extends Component
{
    public $projectId;
    public $dealId;
    public $currentView = 'list'; // list, deadline, planner

    // Planner Data
    public $tasks = [];
    public $statuses = [
        'not_planned' => 'Rejalashtirilmagan (Not planned)',
        'in_progress' => 'Jarayonda (In progress)',
        'this_week' => 'Shu hafta (To be done this week)',
        'review' => 'Ko\'rib chiqish (Review)',
    ];

    // Deadline Data
    public $deadlineTasks = [];
    public $deadlineGroups = [
        'overdue' => 'Muddati o\'tgan (Overdue)',
        'today' => 'Bugun (Due today)',
        'this_week' => 'Shu hafta (Due this week)',
        'next_week' => 'Keyingi hafta (Due next week)',
        'no_deadline' => 'Muddatsiz (No deadline)',
        'over_two_weeks' => '2 haftadan ko\'p (Due over 2 weeks)',
        'completed' => 'Tugallangan (Completed)',
    ];

    // List Data
    public $listTasks = [];

    public $isCreatingTask = false;
    public $isViewingTask = false;
    public $selectedTask = null;

    public $newTaskTitle = '';
    public $newTaskDescription = '';
    public $newTaskPriority = 'medium';
    public $newTaskStartDate = '';
    public $newTaskDueDate = '';
    public $newTaskAssignType = 'user'; // user, team, everyone
    public $newTaskAssigneeIds = [];
    public $newTaskTeamIds = [];
    public $newTaskObservers = [];

    protected $listeners = [
        'assigneeSelected' => 'setAssignee',
        'teamSelected' => 'setTeam',
        'observerSelected' => 'addObserver'
    ];

    public function setAssignee($id, $name, $eventName)
    {
        if ($eventName === 'assigneeSelected' && $id) {
            if (!in_array($id, $this->newTaskAssigneeIds)) {
                $this->newTaskAssigneeIds[] = $id;
            }
        }
    }

    public function setTeam($id, $name, $eventName)
    {
        if ($eventName === 'teamSelected' && $id) {
            if (!in_array($id, $this->newTaskTeamIds)) {
                $this->newTaskTeamIds[] = $id;
            }
        }
    }

    public function addObserver($id, $name, $eventName)
    {
        if ($eventName === 'observerSelected' && $id) {
            if (!in_array($id, $this->newTaskObservers)) {
                $this->newTaskObservers[] = $id;
            }
        }
    }

    public function mount($projectId = null)
    {
        $this->projectId = $projectId;
        $this->dealId = request()->query('dealId');
        $this->loadTasks();
    }

    public function changeView($view)
    {
        if (in_array($view, ['list', 'deadline', 'planner'])) {
            $this->currentView = $view;
            $this->loadTasks();
        }
    }

    public function loadTasks()
    {
        $repository = app(TaskRepositoryInterface::class);
        
        if ($this->dealId) {
            $allTasks = Task::where('deal_id', $this->dealId)->get();
        } else {
            $allTasks = $repository->getAllTasks();
        }
        
        if ($this->currentView === 'planner') {
            $this->tasks = [];
            foreach ($this->statuses as $key => $label) {
                $this->tasks[$key] = $allTasks->where('status', $key)->values();
            }
        } elseif ($this->currentView === 'deadline') {
            $this->deadlineTasks = [];
            foreach (array_keys($this->deadlineGroups) as $key) {
                $this->deadlineTasks[$key] = collect();
            }
            
            $now = \Carbon\Carbon::now();
            foreach ($allTasks as $task) {
                if ($task->status === 'done' || $task->status === 'review') { // mapping completed
                    $this->deadlineTasks['completed']->push($task);
                    continue;
                }
                
                if (!$task->due_date) {
                    $this->deadlineTasks['no_deadline']->push($task);
                    continue;
                }

                $due = \Carbon\Carbon::parse($task->due_date);
                if ($due->isPast() && !$due->isToday()) {
                    $this->deadlineTasks['overdue']->push($task);
                } elseif ($due->isToday()) {
                    $this->deadlineTasks['today']->push($task);
                } elseif ($due->isCurrentWeek()) {
                    $this->deadlineTasks['this_week']->push($task);
                } elseif ($due->isNextWeek()) {
                    $this->deadlineTasks['next_week']->push($task);
                } else {
                    $this->deadlineTasks['over_two_weeks']->push($task);
                }
            }
        } elseif ($this->currentView === 'list') {
            $this->listTasks = $allTasks;
        }
    }

    public function updateTaskStatus($taskId, $newStatus, ChangeTaskStatusAction $action)
    {
        if (array_key_exists($newStatus, $this->statuses)) {
            $action->execute($taskId, $newStatus);
            $this->loadTasks();
        }
    }

    public function updateTaskDeadline($taskId, $groupKey)
    {
        $task = Task::find($taskId);
        if (!$task) return;

        $now = \Carbon\Carbon::now();
        if ($groupKey === 'today') {
            $task->due_date = $now->endOfDay();
        } elseif ($groupKey === 'this_week') {
            $task->due_date = $now->endOfWeek();
        } elseif ($groupKey === 'next_week') {
            $task->due_date = $now->addWeek()->endOfWeek();
        } elseif ($groupKey === 'over_two_weeks') {
            $task->due_date = $now->addWeeks(2)->endOfWeek();
        } elseif ($groupKey === 'no_deadline') {
            $task->due_date = null;
        } elseif ($groupKey === 'completed') {
            $task->status = 'review';
        }
        $task->save();
        $this->loadTasks();
    }

    public function openCreateModal()
    {
        $this->reset([
            'newTaskTitle', 'newTaskDescription', 'newTaskPriority',
            'newTaskStartDate', 'newTaskDueDate', 'newTaskAssignType',
            'newTaskAssigneeIds', 'newTaskTeamIds', 'newTaskObservers'
        ]);
        $this->isCreatingTask = true;
    }

    public function saveTask(CreateTaskAction $action)
    {
        $this->validate([
            'newTaskTitle' => 'required|string|max:255',
            'newTaskPriority' => 'required|in:low,medium,high',
            'newTaskAssignType' => 'required|in:user,team,everyone',
        ], [
            'newTaskTitle.required' => 'Vazifa nomi kiritilishi shart.',
            'newTaskPriority.required' => 'Ustuvorlik tanlanishi shart.',
            'newTaskAssignType.required' => 'Kimga yuborilishi tanlanishi shart.',
        ]);

        // Validate multiple assigns
        if ($this->newTaskAssignType === 'user' && empty($this->newTaskAssigneeIds)) {
            $this->addError('newTaskAssigneeIds', 'Kamida bitta xodim tanlanishi kerak.');
            return;
        }
        if ($this->newTaskAssignType === 'team' && empty($this->newTaskTeamIds)) {
            $this->addError('newTaskTeamIds', 'Kamida bitta jamoa tanlanishi kerak.');
            return;
        }

        $dto = new TaskData(
            title: $this->newTaskTitle,
            deal_id: $this->dealId,
            description: $this->newTaskDescription,
            status: 'not_planned',
            priority: $this->newTaskPriority,
            start_date: $this->newTaskStartDate ?: now()->format('Y-m-d H:i:s'),
            due_date: $this->newTaskDueDate ?: now()->addDay()->format('Y-m-d H:i:s'),
            assigned_to: $this->newTaskAssignType === 'user' ? (count($this->newTaskAssigneeIds) ? $this->newTaskAssigneeIds : [auth()->id()]) : null,
            assigned_to_department_id: $this->newTaskAssignType === 'team' ? $this->newTaskTeamIds : null,
            assigned_to_everyone: $this->newTaskAssignType === 'everyone',
            created_by: auth()->id(),
            observers: $this->newTaskObservers,
            available_to_everyone: false
        );

        $action->execute($dto);
        
        $this->isCreatingTask = false;
        $this->loadTasks();
    }

    public function deleteTask($taskId)
    {
        if (!auth()->user()->can('delete_task') && !auth()->user()->hasRole('Admin')) {
            abort(403, 'Sizda vazifalarni o\'chirish huquqi yo\'q.');
        }
        Task::destroy($taskId);
        $this->loadTasks();
    }

    public function viewTask($taskId)
    {
        $this->selectedTask = Task::with('deal')->find($taskId);
        if ($this->selectedTask) {
            $this->isViewingTask = true;
        }
    }

    public function closeTaskView()
    {
        $this->isViewingTask = false;
        $this->selectedTask = null;
    }

    public function render()
    {
        return view('livewire.task-kanban-board')->layout('layouts.app');
    }
}
