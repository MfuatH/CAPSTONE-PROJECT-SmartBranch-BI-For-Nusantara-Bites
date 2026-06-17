from fastapi import FastAPI
from pydantic import BaseModel
import joblib
import numpy as np
import math
import os

app = FastAPI()

MODEL_DIR = os.path.join(os.path.dirname(__file__), 'models')

try:
    branch_encoder = joblib.load(os.path.join(MODEL_DIR, 'branch_encoder.pkl'))
    product_encoder = joblib.load(os.path.join(MODEL_DIR, 'product_encoder.pkl'))
    category_encoder = joblib.load(os.path.join(MODEL_DIR, 'category_encoder.pkl')) 
    type_encoder = joblib.load(os.path.join(MODEL_DIR, 'type_encoder.pkl'))         
    model = joblib.load(os.path.join(MODEL_DIR, 'forecast_monthly_menu_branch.pkl'))
    print("Mantap! 5 Model dan encoder berhasil dimuat.")
except Exception as e:
    print(f"Gagal load model: {e}")

class ForecastRequest(BaseModel):
    store_id: str
    product_id: str
    category: str   
    type: str       
    unit_price: float
    bulan: int
    tahun: int
    lag_1: float
    lag_3: float
    lag_6: float
    rolling_3: float
    rolling_6: float

@app.post("/api/forecast")
def get_forecast(data: ForecastRequest):
    try:
        encoded_branch = branch_encoder.transform([data.store_id])[0]
        encoded_product = product_encoder.transform([data.product_id])[0]
        encoded_category = category_encoder.transform([data.category])[0]
        encoded_type = type_encoder.transform([data.type])[0]
        
        quarter = math.ceil(data.bulan / 3)
        
        features = np.array([[
            encoded_branch,         # 1
            encoded_product,        # 2
            encoded_category,       # 3
            encoded_type,           # 4
            data.unit_price,        # 5
            data.tahun,             # 6 (Year)
            data.bulan,             # 7 (Month)
            quarter,                # 8 (Quarter)
            data.lag_1,             # 9
            data.lag_3,             # 10
            data.lag_6,             # 11
            data.rolling_3,         # 12
            data.rolling_6          # 13
        ]])
        
        prediksi_qty = model.predict(features)
        
        hasil_akhir = int(np.round(prediksi_qty[0]))
        return {"status": "success", "prediction": hasil_akhir}
        
    except Exception as e:
        return {"status": "error", "message": str(e)}