# Screenshots Guide 📸

This guide explains where to place screenshots in the project and what each section should contain.

## Directory Structure

```
Assets/
├── screenshots/
│   ├── 1-login.png
│   ├── 2-dashboard.png
│   ├── 3-branch-comparison.png
│   ├── 4-forecast.png
│   ├── 5-inventory.png
│   ├── 6-transactions.png
│   └── 7-settings.png
└── SCREENSHOTS_GUIDE.md (this file)
```

## Screenshot Requirements

### 1. Login Screen (`1-login.png`)
- **Size**: 1920x1080 or responsive width
- **Content**: Login form with email/password fields
- **Important**: Show clean, professional interface

### 2. Dashboard (`2-dashboard.png`)
- **Size**: 1920x1080 minimum
- **Content**: Main dashboard with:
  - Sales summary cards
  - Branch metrics
  - Sales trend charts
  - Top-selling items
  - Recent transactions table
- **Important**: Highlight key metrics and visualizations

### 3. Branch Comparison (`3-branch-comparison.png`)
- **Size**: 1920x1080
- **Content**:
  - Branch comparison table/chart
  - KPI metrics per branch
  - Visual ranking of branches
- **Important**: Show comparative analysis features

### 4. Forecast Page (`4-forecast.png`)
- **Size**: 1920x1080
- **Content**:
  - Forecast generation results
  - Predicted quantities table
  - Monthly forecast visualization
- **Important**: Demonstrate AI predictions

### 5. Inventory Management (`5-inventory.png`)
- **Size**: 1920x1080
- **Content**:
  - Stock levels
  - Stock recommendations
  - Low stock alerts
  - Raw materials list
- **Important**: Show inventory optimization

### 6. Transaction History (`6-transactions.png`)
- **Size**: 1920x1080
- **Content**:
  - Transaction records table
  - Import/export functionality
  - Date filters
  - Transaction details
- **Important**: Demonstrate data management

### 7. Settings Page (`7-settings.png`)
- **Size**: 1920x1080
- **Content**:
  - User management
  - System settings
  - Configuration options
  - Data export
- **Important**: Show admin panel

## How to Take Screenshots

### Using Windows Snipping Tool
1. Press `Win + Shift + S`
2. Select the area to capture
3. Save to `Assets/screenshots/` directory
4. Name with format: `N-description.png`

### Using Chrome DevTools
1. Press `F12` to open DevTools
2. Press `Ctrl + Shift + P`
3. Type "screenshot"
4. Select "Capture full page screenshot"

### Best Practices
- ✅ Use consistent color scheme
- ✅ Show actual data (not fake data)
- ✅ Remove sensitive information (emails, IDs)
- ✅ Ensure proper lighting and clarity
- ✅ Maintain consistent window size
- ✅ Use high-resolution display (1920x1080 minimum)

## Reference in README

Screenshots are referenced in the README.md like this:

```markdown
![Dashboard Screenshot](Assets/screenshots/2-dashboard.png)
```

## Size Optimization

For web display, optimize images:

```bash
# Using ImageMagick
convert input.png -resize 1920x1080 output.png

# Using ImageOptim (Mac)
imageoptim Assets/screenshots/*.png
```

---

**Last Updated**: June 2026
**Status**: Ready for screenshots
