from fastapi import FastAPI, HTTPException, Request
from pydantic import BaseModel
from telethon import TelegramClient, events
from telethon.tl.functions.channels import CreateChannelRequest, EditPhotoRequest
from telethon.tl.functions.messages import CreateChatRequest
import os
import asyncio

app = FastAPI()

api_id = int(os.getenv('API_ID', '123456'))
api_hash = os.getenv('API_HASH', 'abcdef')

# Simple session storage in memory for demonstration
# In production, use a secure session store per user (e.g. SQLite per phone number)
clients = {}

class AuthRequest(BaseModel):
    phone: str

class VerifyRequest(BaseModel):
    phone: str
    code: str
    password: str = None

class GroupRequest(BaseModel):
    phone: str = None
    title: str
    description: str = ""
    task_id: int = None
    company_id: int = None

@app.get("/")
def read_root():
    return {"status": "Telethon Service Running"}

async def get_client(phone: str):
    if phone not in clients:
        # Create a new session file for this phone
        client = TelegramClient(f'session_{phone}', api_id, api_hash)
        await client.connect()
        clients[phone] = client
    return clients[phone]

@app.post("/api/auth/send_code")
async def send_code(req: AuthRequest):
    client = await get_client(req.phone)
    if not await client.is_user_authorized():
        await client.send_code_request(req.phone)
        return {"status": "ok", "message": f"Code sent to {req.phone}"}
    return {"status": "ok", "message": "Already authorized"}

@app.post("/api/auth/verify")
async def verify_code(req: VerifyRequest):
    client = await get_client(req.phone)
    try:
        await client.sign_in(req.phone, req.code, password=req.password)
        return {"status": "ok", "message": "Verified successfully"}
    except Exception as e:
        raise HTTPException(status_code=400, detail=str(e))

@app.post("/api/groups/create")
async def create_group(req: GroupRequest):
    client = await get_client(req.phone)
    if not await client.is_user_authorized():
        raise HTTPException(status_code=401, detail="User not authorized in Telethon")
    
    try:
        # Create a supergroup (channel with megagroup flag)
        result = await client(CreateChannelRequest(
            title=req.title,
            about=req.description,
            megagroup=True
        ))
        
        chat_id = result.chats[0].id
        
        return {"status": "ok", "chat_id": chat_id, "title": req.title}
    except Exception as e:
        raise HTTPException(status_code=400, detail=str(e))

@app.post("/create-group")
async def generic_create_group(req: GroupRequest):
    # This acts as the webhook receiver from Laravel
    # In reality, it should use the Admin's Telethon session to create a group
    # For now we simulate success and return a dummy link.
    print(f"Creating group for Task {req.task_id} in Company {req.company_id}...")
    return {
        "status": "ok", 
        "group_link": f"https://t.me/+dummyLinkForTask{req.task_id}",
        "title": req.title
    }
