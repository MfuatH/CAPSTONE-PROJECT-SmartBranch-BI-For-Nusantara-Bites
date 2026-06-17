# API Documentation 📡

Comprehensive API documentation for SmartBranch BI backend services.

## Overview

SmartBranch BI provides two main API services:

1. **Laravel REST API** - Web application backend
2. **FastAPI ML Service** - Machine Learning inference API

---

## Laravel REST API

### Base URL

```
Production: https://nusantara-bites.infinityfree.io
Development: http://localhost:8000
```

### Authentication

All endpoints (except login) require authentication using Laravel session-based auth.

**Login Endpoint:**

```http
POST /login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}
```

### API Routes

#### 1. Dashboard Routes

**Get Dashboard Data**

```http
GET /dashboard
```

**Response:**
```json
{
  "total_sales": 5000000,
  "transaction_count": 1250,
  "average_order_value": 4000,
  "branches": [
    {
      "id": 1,
      "location": "Surabaya",
      "sales": 1200000,
      "transactions": 300
    }
  ],
  "top_products": [
    {
      "id": "PROD001",
      "name": "Nasi Goreng",
      "quantity_sold": 450,
      "revenue": 900000
    }
  ],
  "recent_transactions": [...]
}
```

#### 2. Branch Comparison Routes

**Get Branch Comparison**

```http
GET /comparison
```

**Query Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `start_date` | date | Start date (YYYY-MM-DD) |
| `end_date` | date | End date (YYYY-MM-DD) |
| `metric` | string | Metric to compare (sales, transactions, avg_value) |

**Response:**
```json
{
  "branches": [
    {
      "id": 1,
      "location": "Surabaya",
      "sales": 5000000,
      "transactions": 1250,
      "ranking": 1
    }
  ],
  "total_sales": 25000000,
  "comparison_period": "2026-01-01 to 2026-06-30"
}
```

#### 3. Forecast Routes

**Run Forecast Generation**

```http
POST /run-ai-forecast
Content-Type: application/json

{
  "month": 7,
  "year": 2026
}
```

**Response:**
```json
{
  "status": "success",
  "message": "Forecast generated successfully",
  "generated_count": 80,
  "timestamp": "2026-06-17T10:30:00Z"
}
```

**Get Forecast Results**

```http
GET /forecast?store_id=1&month=7&year=2026
```

**Response:**
```json
{
  "forecasts": [
    {
      "id": 1,
      "store_id": 1,
      "product_id": "PROD001",
      "product_name": "Nasi Goreng",
      "predicted_qty": 165,
      "month": 7,
      "year": 2026
    }
  ]
}
```

#### 4. Transaction Routes

**Get Transactions**

```http
GET /riwayat-penjualan?page=1&limit=15
```

**Query Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `page` | int | Page number |
| `limit` | int | Items per page |
| `store_id` | int | Filter by store |
| `date_from` | date | Filter from date |
| `date_to` | date | Filter to date |

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "store_id": 1,
      "product_id": "PROD001",
      "product_name": "Nasi Goreng",
      "quantity": 5,
      "unit_price": 45000,
      "total": 225000,
      "transaction_date": "2026-06-15",
      "transaction_time": "12:30:00"
    }
  ],
  "pagination": {
    "current_page": 1,
    "total_pages": 10,
    "total_records": 150
  }
}
```

**Import Transactions**

```http
POST /import-dataset
Content-Type: multipart/form-data

file: [Excel file]
```

**Response:**
```json
{
  "status": "success",
  "imported_count": 500,
  "failed_count": 0,
  "message": "Dataset imported successfully"
}
```

#### 5. Inventory Routes

**Get Inventory Status**

```http
GET /stok-inventaris?store_id=1
```

**Response:**
```json
{
  "store": {
    "id": 1,
    "location": "Surabaya"
  },
  "raw_materials": [
    {
      "id": 1,
      "name": "Beras Premium",
      "sku": "RM001",
      "unit": "kg",
      "current_stock": 500,
      "minimum_stock": 100,
      "forecast_qty": 520,
      "price_per_unit": 15000,
      "status": "optimal"
    }
  ]
}
```

**Update Stock Level**

```http
PUT /inventory/{raw_material_id}/store/{store_id}
Content-Type: application/json

{
  "current_stock": 520,
  "minimum_stock": 150
}
```

#### 6. Settings Routes

**Get Settings**

```http
GET /settings
```

**Update Settings**

```http
POST /settings
Content-Type: application/json

{
  "setting_key": "value"
}
```

---

## FastAPI ML Service

### Base URL

```
Development: http://localhost:8000
Production: [Your ML API URL]
```

### Interactive Documentation

```
Swagger UI: http://localhost:8000/docs
ReDoc: http://localhost:8000/redoc
```

### Endpoints

#### Forecast Prediction API

**Endpoint:** `POST /api/forecast`

**Description:** Generate sales quantity prediction for a menu item in a specific branch.

**Request Header:**
```
Content-Type: application/json
```

**Request Body:**
```json
{
  "store_id": "SURABAYA",
  "product_id": "PROD001",
  "category": "FOOD",
  "type": "MAIN_COURSE",
  "unit_price": 45000.0,
  "bulan": 7,
  "tahun": 2026,
  "lag_1": 156.0,
  "lag_3": 145.5,
  "lag_6": 140.2,
  "rolling_3": 150.3,
  "rolling_6": 148.5
}
```

**Response (Success):**
```json
{
  "status": "success",
  "prediction": 165
}
```

**Response (Error):**
```json
{
  "status": "error",
  "message": "Invalid store_id: INVALID_STORE"
}
```

**Status Codes:**

| Code | Description |
|------|-------------|
| 200 | Successful prediction |
| 400 | Invalid request parameters |
| 500 | Server error |

**Example cURL Request:**

```bash
curl -X POST "http://localhost:8000/api/forecast" \
  -H "Content-Type: application/json" \
  -d '{
    "store_id": "SURABAYA",
    "product_id": "PROD001",
    "category": "FOOD",
    "type": "MAIN_COURSE",
    "unit_price": 45000,
    "bulan": 7,
    "tahun": 2026,
    "lag_1": 156.0,
    "lag_3": 145.5,
    "lag_6": 140.2,
    "rolling_3": 150.3,
    "rolling_6": 148.5
  }'
```

**Example Python Request:**

```python
import requests
import json

url = "http://localhost:8000/api/forecast"

payload = {
    "store_id": "SURABAYA",
    "product_id": "PROD001",
    "category": "FOOD",
    "type": "MAIN_COURSE",
    "unit_price": 45000.0,
    "bulan": 7,
    "tahun": 2026,
    "lag_1": 156.0,
    "lag_3": 145.5,
    "lag_6": 140.2,
    "rolling_3": 150.3,
    "rolling_6": 148.5
}

response = requests.post(url, json=payload)
print(response.json())
```

**Example JavaScript Request:**

```javascript
const url = 'http://localhost:8000/api/forecast';

const payload = {
  store_id: 'SURABAYA',
  product_id: 'PROD001',
  category: 'FOOD',
  type: 'MAIN_COURSE',
  unit_price: 45000,
  bulan: 7,
  tahun: 2026,
  lag_1: 156.0,
  lag_3: 145.5,
  lag_6: 140.2,
  rolling_3: 150.3,
  rolling_6: 148.5
};

fetch(url, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify(payload)
})
.then(response => response.json())
.then(data => console.log(data));
```

### Error Handling

**Common Error Scenarios:**

1. **Missing Required Field**
```json
{
  "status": "error",
  "message": "Field 'store_id' is required"
}
```

2. **Invalid Store ID**
```json
{
  "status": "error",
  "message": "Invalid store_id: INVALID_STORE. Valid options: SURABAYA, BANDUNG, YOGYAKARTA, SEMARANG, MALANG"
}
```

3. **Server Error**
```json
{
  "status": "error",
  "message": "An error occurred during prediction. Please try again."
}
```

---

## Integration Examples

### Example 1: Batch Forecast Generation

```python
import requests
from datetime import datetime

stores = ['SURABAYA', 'BANDUNG', 'YOGYAKARTA', 'SEMARANG', 'MALANG']
products = ['PROD001', 'PROD002', 'PROD003']

for store in stores:
    for product in products:
        payload = {
            "store_id": store,
            "product_id": product,
            "category": "FOOD",
            "type": "MAIN_COURSE",
            "unit_price": 45000,
            "bulan": 7,
            "tahun": 2026,
            "lag_1": 156.0,
            "lag_3": 145.5,
            "lag_6": 140.2,
            "rolling_3": 150.3,
            "rolling_6": 148.5
        }
        
        response = requests.post('http://localhost:8000/api/forecast', json=payload)
        print(f"{store} - {product}: {response.json()}")
```

### Example 2: Django Integration

```python
import requests

def get_forecast(store_id, product_id, **kwargs):
    """Get forecast from ML API and save to database"""
    
    url = 'http://ml-api.yourdomain.com:8000/api/forecast'
    
    data = {
        'store_id': store_id,
        'product_id': product_id,
        **kwargs
    }
    
    response = requests.post(url, json=data)
    
    if response.status_code == 200:
        result = response.json()
        if result['status'] == 'success':
            return result['prediction']
    
    return None
```

### Example 3: JavaScript/Node.js Integration

```javascript
async function getForecast(storeId, productId, data) {
  try {
    const response = await fetch('http://localhost:8000/api/forecast', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        store_id: storeId,
        product_id: productId,
        ...data
      })
    });
    
    const result = await response.json();
    
    if (result.status === 'success') {
      return result.prediction;
    } else {
      throw new Error(result.message);
    }
  } catch (error) {
    console.error('Forecast error:', error);
    return null;
  }
}
```

---

## Rate Limiting

- **Login Endpoint**: 5 requests per minute
- **ML API**: 100 requests per minute
- **Other Endpoints**: 60 requests per minute

Exceeding limits returns HTTP 429 (Too Many Requests).

---

## Versioning

Current API Version: **v1.0**

Future versions will be available at:
- `/api/v2/forecast`
- `/api/v2/branches`

---

## Support

For API issues and questions:

- 📧 Email: api-support@smartbranch.com
- 🐛 GitHub Issues: [Submit Issue](https://github.com/smartbranch-bi/issues)
- 📖 Documentation: See full docs in README.md

---

**Last Updated**: June 2026
