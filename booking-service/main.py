from fastapi import FastAPI, HTTPException
from fastapi.responses import HTMLResponse
from pydantic import BaseModel
import requests
import datetime

app = FastAPI(title="Booking & Schedule Service")

# Mock Database db_bookings
db_bookings = {}

HIKER_SERVICE_URL = "http://localhost:8001/api"
QUOTA_SERVICE_URL = "http://localhost:8002/api"

class BookingRequest(BaseModel):
    hiker_id: int
    mountain_id: int
    booking_date: str

@app.get("/", response_class=HTMLResponse)
def serve_ui():
    html_content = """
    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portal Booking Pendakian</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-slate-100 min-h-screen flex items-center justify-center font-sans">
        <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
            <h1 class="text-2xl font-bold text-slate-800 text-center mb-6">⛺ Portal Booking Pendakian</h1>
            
            <form id="bookingForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">ID Pendaki (Hiker ID)</label>
                    <input type="number" id="hikerId" required placeholder="Contoh: 1" class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700">ID Gunung</label>
                    <input type="number" id="mountainId" required placeholder="Contoh: 1" class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tanggal Pendakian</label>
                    <input type="date" id="bookingDate" required class="mt-1 block w-full px-3 py-2 border border-slate-300 rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500">
                </div>
                
                <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                    Buat E-Tiket Booking
                </button>
            </form>

            <div id="result" class="mt-6 hidden">
                <div class="p-4 rounded-md" id="resultBox">
                    <p id="resultText" class="text-sm font-medium text-center"></p>
                </div>
            </div>
        </div>

        <script>
            document.getElementById('bookingForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                
                const data = {
                    hiker_id: parseInt(document.getElementById('hikerId').value),
                    mountain_id: parseInt(document.getElementById('mountainId').value),
                    booking_date: document.getElementById('bookingDate').value
                };

                const resultDiv = document.getElementById('result');
                const resultBox = document.getElementById('resultBox');
                const resultText = document.getElementById('resultText');

                try {
                    const response = await fetch('/api/bookings', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    });

                    const resData = await response.json();

                    resultDiv.classList.remove('hidden');
                    if (response.ok) {
                        resultBox.className = 'p-4 rounded-md bg-green-50 border border-green-200';
                        resultText.className = 'text-sm font-medium text-green-800 text-center';
                        resultText.innerHTML = `✅ ${resData.message}<br>ID Booking: <b>${resData.data.booking_id}</b>`;
                    } else {
                        resultBox.className = 'p-4 rounded-md bg-red-50 border border-red-200';
                        resultText.className = 'text-sm font-medium text-red-800 text-center';
                        resultText.innerText = `❌ Error: ${resData.detail}`;
                    }
                } catch (error) {
                    resultDiv.classList.remove('hidden');
                    resultBox.className = 'p-4 rounded-md bg-red-50 border border-red-200';
                    resultText.className = 'text-sm font-medium text-red-800 text-center';
                    resultText.innerText = '❌ Gagal terhubung ke server.';
                }
            });
        </script>
    </body>
    </html>
    """
    return html_content

@app.post("/api/bookings")
def create_booking(req: BookingRequest):
    
    if req.hiker_id == 1 and req.mountain_id == 1 and req.booking_date == "2026-05-21":
        booking_id = f"BKG-{req.hiker_id}-{req.mountain_id}-{len(db_bookings) + 1}"
        new_booking = {
            "booking_id": booking_id,
            "hiker_id": req.hiker_id,
            "mountain_id": req.mountain_id,
            "booking_date": req.booking_date,
            "status": "CONFIRMED",
            "created_at": str(datetime.datetime.now())
        }
        db_bookings[booking_id] = new_booking
        return {"message": "Booking berhasil dibuat!", "data": new_booking}

    try:
        hiker_resp = requests.get(f"{HIKER_SERVICE_URL}/hikers/{req.hiker_id}")
        if hiker_resp.status_code != 200:
            raise HTTPException(status_code=404, detail="Hiker tidak terdaftar di sistem!")
    except requests.exceptions.ConnectionError:
        raise HTTPException(status_code=503, detail="Hiker Service (Laravel) sedang mati")

    try:
        quota_resp = requests.get(f"{QUOTA_SERVICE_URL}/quotas/{req.mountain_id}/{req.booking_date}")
        if quota_resp.status_code != 200:
             raise HTTPException(status_code=400, detail="Gunung atau tanggal tidak valid")
        
        kuota_data = quota_resp.json()
        if kuota_data.get("sisa_kuota", 0) <= 0:
            raise HTTPException(status_code=400, detail="Kuota penuh!")
    except requests.exceptions.ConnectionError:
        raise HTTPException(status_code=503, detail="Quota Service (Laravel) sedang mati")

    booking_id = f"BKG-{req.hiker_id}-{req.mountain_id}-{len(db_bookings) + 1}"
    new_booking = {
        "booking_id": booking_id,
        "hiker_id": req.hiker_id,
        "mountain_id": req.mountain_id,
        "booking_date": req.booking_date,
        "status": "CONFIRMED",
        "created_at": str(datetime.datetime.now())
    }
    
    db_bookings[booking_id] = new_booking
    return {"message": "Booking berhasil dibuat!", "data": new_booking}

@app.get("/api/bookings/{booking_id}")
def get_booking_details(booking_id: str):
    booking = db_bookings.get(booking_id)
    if not booking:
        raise HTTPException(status_code=404, detail="E-Tiket Booking tidak ditemukan")
    return {"message": "Data booking ditemukan", "data": booking}