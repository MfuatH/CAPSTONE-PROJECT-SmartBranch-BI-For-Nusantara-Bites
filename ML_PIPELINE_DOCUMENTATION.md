# Machine Learning Pipeline Documentation 🤖

Detailed documentation of the ML pipeline, model architecture, training process, and deployment.

## Table of Contents

- [Overview](#overview)
- [Data Pipeline](#data-pipeline)
- [Feature Engineering](#feature-engineering)
- [Model Architecture](#model-architecture)
- [Training Process](#training-process)
- [Model Evaluation](#model-evaluation)
- [Model Deployment](#model-deployment)
- [Retraining Guide](#retraining-guide)
- [Troubleshooting](#troubleshooting)

---

## Overview

### Objective

Predict monthly sales quantity for each menu item (product) across 5 restaurant branches to enable:
- Accurate demand forecasting
- Inventory optimization
- Stock recommendation system
- Revenue planning

### Model Type

**Regression Model** - Predicts continuous numerical values (quantity)

### Framework & Libraries

- **Language**: Python 3.8+
- **ML Framework**: Scikit-learn
- **Data Processing**: Pandas, NumPy
- **Serialization**: Joblib
- **API Framework**: FastAPI

---

## Data Pipeline

### Data Sources

The model is trained on multi-source historical restaurant sales data:

#### 1. Restaurant Sales Data (Rohit Grewal - Kaggle)

**Attributes:**
- Transaction ID
- Store/Branch Name
- Product Name
- Transaction Date
- Quantity Sold
- Unit Price
- Total Amount
- Payment Method
- Customer Type

**Usage**: Branch-level analysis, revenue patterns, customer behavior

#### 2. Restaurant Sales Report 2024-2025 (Alexander Chen - Kaggle)

**Attributes:**
- Date
- Product/Menu Item
- Category
- Type
- Quantity Sold
- Unit Price
- Promo Flag
- Sales Conditions

**Usage**: Time series pattern, seasonality, forecasting model training

#### 3. Coffee Sales Dataset (Ahmed Abbas - Kaggle)

**Attributes:**
- Transaction Date
- Product
- Category
- Quantity
- Unit Price
- Payment Method

**Usage**: F&B industry patterns, feature enrichment

### Data Processing Steps

```
Raw Data → Cleaning → Normalization → Aggregation → Feature Engineering → Model Training
```

#### Step 1: Data Cleaning

```python
import pandas as pd

def clean_data(df):
    """Remove duplicates, handle missing values, validate data types"""
    # Remove duplicates
    df = df.drop_duplicates()
    
    # Handle missing values
    df['quantity'].fillna(df['quantity'].median(), inplace=True)
    df['unit_price'].fillna(df['unit_price'].mean(), inplace=True)
    
    # Remove outliers
    Q1 = df['quantity'].quantile(0.25)
    Q3 = df['quantity'].quantile(0.75)
    IQR = Q3 - Q1
    df = df[(df['quantity'] >= Q1 - 1.5*IQR) & (df['quantity'] <= Q3 + 1.5*IQR)]
    
    return df
```

#### Step 2: Data Normalization

```python
from sklearn.preprocessing import StandardScaler

def normalize_features(X):
    """Standardize numerical features"""
    scaler = StandardScaler()
    X_scaled = scaler.fit_transform(X[['unit_price', 'quantity']])
    return X_scaled, scaler
```

#### Step 3: Data Aggregation

Aggregate transaction-level data to product-branch-month level:

```python
def aggregate_monthly_data(df):
    """Aggregate daily transactions to monthly level"""
    df['date'] = pd.to_datetime(df['transaction_date'])
    df['year_month'] = df['date'].dt.to_period('M')
    
    monthly = df.groupby(['year_month', 'store_id', 'product_id']).agg({
        'quantity': 'sum',
        'unit_price': 'mean'
    }).reset_index()
    
    return monthly
```

---

## Feature Engineering

### Features Used

The model uses 13 features derived from sales history:

#### 1. **Categorical Features** (Encoded)

| Feature | Description | Values |
|---------|-------------|--------|
| `branch_encoded` | Branch identifier | Numeric: 0-4 |
| `product_encoded` | Product identifier | Numeric: 0-15 |
| `category_encoded` | Product category | Numeric (FOOD, BEVERAGE, etc.) |
| `type_encoded` | Product type | Numeric (MAIN_COURSE, DESSERT, etc.) |

**Encoding Method**: LabelEncoder

```python
from sklearn.preprocessing import LabelEncoder

branch_encoder = LabelEncoder()
product_encoder = LabelEncoder()
category_encoder = LabelEncoder()
type_encoder = LabelEncoder()

# Fit encoders on training data
branch_encoder.fit(df['store_id'].unique())
product_encoder.fit(df['product_id'].unique())
category_encoder.fit(df['category'].unique())
type_encoder.fit(df['type'].unique())

# Transform data
df['branch_encoded'] = branch_encoder.transform(df['store_id'])
df['product_encoded'] = product_encoder.transform(df['product_id'])
```

#### 2. **Temporal Features**

| Feature | Type | Description | Range |
|---------|------|-------------|-------|
| `unit_price` | float | Product unit price | 0 - 500,000 |
| `year` | int | Year | 2024-2026 |
| `month` | int | Month | 1-12 |
| `quarter` | int | Quarter | 1-4 |

#### 3. **Lag Features** (Previous Sales)

| Feature | Type | Description |
|---------|------|-------------|
| `lag_1` | float | Sales quantity 1 month ago |
| `lag_3` | float | Average sales 3 months ago |
| `lag_6` | float | Average sales 6 months ago |

**Calculation:**
```python
def create_lag_features(df):
    """Create lag features for time series"""
    df = df.sort_values(['store_id', 'product_id', 'date'])
    
    # Lag features
    df['lag_1'] = df.groupby(['store_id', 'product_id'])['quantity'].shift(1)
    df['lag_3'] = df.groupby(['store_id', 'product_id'])['quantity'].shift(1).rolling(3).mean()
    df['lag_6'] = df.groupby(['store_id', 'product_id'])['quantity'].shift(1).rolling(6).mean()
    
    return df
```

#### 4. **Rolling Average Features**

| Feature | Type | Description |
|---------|------|-------------|
| `rolling_3` | float | 3-month rolling average |
| `rolling_6` | float | 6-month rolling average |

**Calculation:**
```python
def create_rolling_features(df):
    """Create rolling average features"""
    df['rolling_3'] = df.groupby(['store_id', 'product_id'])['quantity'].rolling(3).mean().reset_index(drop=True)
    df['rolling_6'] = df.groupby(['store_id', 'product_id'])['quantity'].rolling(6).mean().reset_index(drop=True)
    
    return df
```

### Feature Matrix

```python
# Final feature matrix for model input
features = [
    'branch_encoded',      # 1
    'product_encoded',     # 2
    'category_encoded',    # 3
    'type_encoded',        # 4
    'unit_price',          # 5
    'year',                # 6
    'month',               # 7
    'quarter',             # 8
    'lag_1',               # 9
    'lag_3',               # 10
    'lag_6',               # 11
    'rolling_3',           # 12
    'rolling_6'            # 13
]

X = df[features]
y = df['quantity']  # Target variable
```

---

## Model Architecture

### Model Selection

**Algorithm**: Random Forest Regressor (Ensemble method)

**Why Random Forest?**
- Handles non-linear relationships
- Robust to outliers
- Feature importance analysis
- High accuracy for regression tasks
- Less prone to overfitting

### Hyperparameters

```python
from sklearn.ensemble import RandomForestRegressor

model = RandomForestRegressor(
    n_estimators=200,      # Number of trees
    max_depth=20,          # Maximum tree depth
    min_samples_split=5,   # Minimum samples to split
    min_samples_leaf=2,    # Minimum samples at leaf
    random_state=42,       # Reproducibility
    n_jobs=-1,             # Use all CPU cores
    verbose=1
)
```

### Model Training Pipeline

```python
from sklearn.pipeline import Pipeline
from sklearn.preprocessing import StandardScaler

pipeline = Pipeline([
    ('scaler', StandardScaler()),
    ('model', RandomForestRegressor(n_estimators=200))
])

# Train model
pipeline.fit(X_train, y_train)

# Save model and encoders
import joblib
joblib.dump(pipeline, 'forecast_monthly_menu_branch.pkl')
joblib.dump(branch_encoder, 'branch_encoder.pkl')
joblib.dump(product_encoder, 'product_encoder.pkl')
joblib.dump(category_encoder, 'category_encoder.pkl')
joblib.dump(type_encoder, 'type_encoder.pkl')
```

---

## Training Process

### Data Split

```python
from sklearn.model_selection import train_test_split

# 80-20 split (time series aware)
split_point = int(len(df) * 0.8)
train_data = df[:split_point]
test_data = df[split_point:]

X_train = train_data[features]
y_train = train_data['quantity']
X_test = test_data[features]
y_test = test_data['quantity']
```

### Training Steps

```python
import numpy as np

# 1. Feature engineering
X_train = create_lag_features(X_train)
X_test = create_lag_features(X_test)

# 2. Remove NaN values (from lag features)
mask = ~np.isnan(X_train.astype(float)).any(axis=1)
X_train = X_train[mask]
y_train = y_train[mask]

# 3. Fit encoders on training data
branch_encoder.fit(X_train['store_id'])
product_encoder.fit(X_train['product_id'])

# 4. Transform categorical features
X_train['branch_encoded'] = branch_encoder.transform(X_train['store_id'])
X_test['branch_encoded'] = branch_encoder.transform(X_test['store_id'])

# 5. Train model
model.fit(X_train[features], y_train)

# 6. Evaluate
y_pred = model.predict(X_test[features])
evaluate_model(y_test, y_pred)
```

---

## Model Evaluation

### Performance Metrics

#### 1. R² Score (Coefficient of Determination)

```python
from sklearn.metrics import r2_score

r2 = r2_score(y_test, y_pred)
# Result: 0.9860 (98.6% of variance explained)
```

**Interpretation**: Model explains 98.6% of variance in sales quantities. Excellent performance!

#### 2. Mean Absolute Error (MAE)

```python
from sklearn.metrics import mean_absolute_error

mae = mean_absolute_error(y_test, y_pred)
# Result: 9.568
```

**Interpretation**: On average, predictions are off by ±9.6 units.

#### 3. Root Mean Squared Error (RMSE)

```python
from sklearn.metrics import mean_squared_error

rmse = np.sqrt(mean_squared_error(y_test, y_pred))
# Result: 14.306
```

**Interpretation**: Penalizes larger errors more; shows model's overall accuracy.

#### 4. Mean Absolute Percentage Error (MAPE)

```python
def mean_absolute_percentage_error(y_true, y_pred):
    return np.mean(np.abs((y_true - y_pred) / y_true)) * 100

mape = mean_absolute_percentage_error(y_test, y_pred)
# Result: 15.34%
```

**Interpretation**: Average percentage error relative to actual values.

### Evaluation Results Table

| Metric | Value | Status | Threshold |
|--------|-------|--------|-----------|
| **R² Score** | 0.9860 | ✅ Excellent | > 0.85 |
| **MAE** | 9.568 | ✅ Good | < 15 |
| **RMSE** | 14.306 | ✅ Good | < 20 |
| **MAPE** | 15.34% | ✅ Good | < 20% |

### Cross-Validation

```python
from sklearn.model_selection import cross_val_score

cv_scores = cross_val_score(model, X_train, y_train, cv=5, scoring='r2')
print(f"Cross-validation R² scores: {cv_scores}")
print(f"Mean: {cv_scores.mean():.4f} (+/- {cv_scores.std():.4f})")
```

### Residual Analysis

```python
import matplotlib.pyplot as plt

residuals = y_test - y_pred

plt.figure(figsize=(12, 4))

# Plot 1: Residuals vs Predicted
plt.subplot(1, 2, 1)
plt.scatter(y_pred, residuals)
plt.axhline(y=0, color='r', linestyle='--')
plt.xlabel('Predicted Values')
plt.ylabel('Residuals')

# Plot 2: Residuals Distribution
plt.subplot(1, 2, 2)
plt.hist(residuals, bins=30)
plt.xlabel('Residuals')
plt.ylabel('Frequency')

plt.tight_layout()
plt.show()
```

---

## Model Deployment

### Serialization

```python
import joblib

# Save model and encoders
joblib.dump(model, 'models/forecast_monthly_menu_branch.pkl')
joblib.dump(branch_encoder, 'models/branch_encoder.pkl')
joblib.dump(product_encoder, 'models/product_encoder.pkl')
joblib.dump(category_encoder, 'models/category_encoder.pkl')
joblib.dump(type_encoder, 'models/type_encoder.pkl')

print("✅ Model and encoders saved successfully!")
```

### Loading for Inference

```python
# In FastAPI main.py
model = joblib.load('models/forecast_monthly_menu_branch.pkl')
branch_encoder = joblib.load('models/branch_encoder.pkl')
product_encoder = joblib.load('models/product_encoder.pkl')
category_encoder = joblib.load('models/category_encoder.pkl')
type_encoder = joblib.load('models/type_encoder.pkl')
```

### FastAPI Integration

See `FastAPI/main.py` for complete API implementation.

### Model Serving Options

#### Option 1: FastAPI (Current)
- Fast inference
- Easy integration
- Real-time predictions

#### Option 2: TensorFlow Serving
- Optimized for deep learning
- Advanced versioning
- A/B testing support

#### Option 3: MLflow
- Experiment tracking
- Model registry
- Production deployment

---

## Retraining Guide

### When to Retrain

- **Scheduled**: Monthly retraining with new data
- **Triggered**: When accuracy drops below threshold
- **Manual**: On-demand retraining

### Retraining Steps

```python
def retrain_model():
    """Monthly model retraining pipeline"""
    
    # 1. Collect new data
    new_data = get_latest_transactions()  # From database
    
    # 2. Preprocess
    new_data = clean_data(new_data)
    new_data = create_lag_features(new_data)
    new_data = create_rolling_features(new_data)
    
    # 3. Feature engineering
    X = new_data[features]
    y = new_data['quantity']
    
    # 4. Split data
    X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2)
    
    # 5. Train new model
    new_model = RandomForestRegressor(n_estimators=200)
    new_model.fit(X_train, y_train)
    
    # 6. Evaluate
    y_pred = new_model.predict(X_test)
    new_r2 = r2_score(y_test, y_pred)
    
    # 7. Compare with old model
    old_model = joblib.load('models/forecast_monthly_menu_branch.pkl')
    old_y_pred = old_model.predict(X_test)
    old_r2 = r2_score(y_test, old_y_pred)
    
    # 8. Deploy if better
    if new_r2 > old_r2:
        # Backup old model
        shutil.copy('models/forecast_monthly_menu_branch.pkl', 
                   'models/forecast_monthly_menu_branch.backup.pkl')
        
        # Save new model
        joblib.dump(new_model, 'models/forecast_monthly_menu_branch.pkl')
        
        print(f"✅ Model updated! R² improved from {old_r2:.4f} to {new_r2:.4f}")
        return True
    else:
        print(f"❌ Model not improved. Keeping old model.")
        return False
```

### Automated Retraining (Cron Job)

```bash
# crontab -e
# Run retraining first day of month at 2 AM
0 2 1 * * cd /path/to/smartbranch && python retrain_model.py
```

---

## Troubleshooting

### Issue 1: High Prediction Error

**Symptoms**: RMSE > 20, MAPE > 20%

**Solutions:**
1. Check data quality
2. Add more features
3. Increase training data
4. Adjust hyperparameters
5. Try different algorithm

### Issue 2: Model Doesn't Load

**Error**: `FileNotFoundError: model not found`

**Solution:**
```bash
# Check model files exist
ls -la models/
python -c "import joblib; joblib.load('models/forecast_monthly_menu_branch.pkl')"
```

### Issue 3: Slow Predictions

**Symptoms**: API response > 1 second

**Solutions:**
1. Reduce n_estimators (fewer trees)
2. Use model quantization
3. Deploy with Gunicorn workers
4. Add response caching

### Issue 4: Encoder Mismatch

**Error**: `ValueError: y contains previously unseen labels`

**Solution:**
```python
# Use unknown_output_handler
encoder.transform(['unknown_value'])  # Will raise error
# Fix: Add value during training or use fallback
```

---

## Performance Tips

1. **Cache model in memory** - Load once on startup
2. **Batch predictions** - Process multiple at once
3. **Use numpy operations** - Faster than pandas for large arrays
4. **Quantize model** - Reduce file size and memory
5. **Monitor predictions** - Track accuracy over time

---

**Last Updated**: June 2026
**Model Version**: 1.0.0
