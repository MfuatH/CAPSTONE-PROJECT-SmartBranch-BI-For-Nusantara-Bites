# Contributing to SmartBranch BI 🤝

Thank you for your interest in contributing to SmartBranch BI! This document provides guidelines and instructions for contributing to the project.

## Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [Making Changes](#making-changes)
- [Submitting Changes](#submitting-changes)
- [Code Style Guidelines](#code-style-guidelines)
- [Commit Message Convention](#commit-message-convention)
- [Pull Request Process](#pull-request-process)
- [Reporting Issues](#reporting-issues)
- [Feature Requests](#feature-requests)

## Code of Conduct

### Our Commitment

We are committed to providing a welcoming and inclusive environment for all contributors. We expect all participants to:

- Use welcoming and inclusive language
- Be respectful of differing opinions and experiences
- Accept constructive criticism gracefully
- Focus on what is best for the community
- Show empathy towards other community members

### Unacceptable Behavior

The following behaviors are considered unacceptable:

- Harassment, discrimination, or intimidation
- Offensive comments related to gender, sexual orientation, disability, race, or religion
- Unwelcome sexual attention or advances
- Trolling or inflammatory comments
- Publishing others' private information without consent

### Enforcement

Instances of unacceptable behavior may result in being banned from the project.

## Getting Started

### Prerequisites

Before contributing, ensure you have:

- Git installed
- PHP 8.3+
- Node.js 18+
- Python 3.8+
- MySQL 5.7+
- Composer installed
- Basic knowledge of Laravel and FastAPI

### Fork & Clone

1. **Fork the repository** on GitHub
   ```bash
   # Click "Fork" button on GitHub repository
   ```

2. **Clone your fork**
   ```bash
   git clone https://github.com/yourusername/smartbranch-bi.git
   cd smartbranch-bi
   ```

3. **Add upstream remote**
   ```bash
   git remote add upstream https://github.com/original-owner/smartbranch-bi.git
   ```

## Development Setup

### Backend Setup (Laravel)

```bash
cd Laravel

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Setup database
php artisan migrate --seed

# Start development server
php artisan serve
```

### Frontend Setup

```bash
cd Laravel

# Install dependencies
npm install

# Start Vite dev server
npm run dev
```

### ML API Setup (FastAPI)

```bash
cd ../FastAPI

# Create virtual environment
python -m venv venv

# Activate virtual environment
# On Windows:
venv\Scripts\activate
# On macOS/Linux:
source venv/bin/activate

# Install dependencies
pip install -r requirements.txt

# Run FastAPI server
uvicorn main:app --reload
```

## Making Changes

### Creating a Feature Branch

```bash
# Update main branch
git fetch upstream
git checkout main
git merge upstream/main

# Create feature branch
git checkout -b feature/your-feature-name

# Or for bug fixes
git checkout -b fix/bug-description
```

### Branch Naming Convention

- **Features**: `feature/short-description`
- **Bug Fixes**: `fix/bug-description`
- **Documentation**: `docs/documentation-type`
- **Refactoring**: `refactor/component-name`
- **Performance**: `perf/optimization-type`

Example:
```bash
git checkout -b feature/add-email-notifications
git checkout -b fix/forecast-model-accuracy
git checkout -b docs/api-documentation
```

## Code Style Guidelines

### PHP/Laravel

Follow **PSR-12** coding standard:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = Product::paginate(15);
        
        return view('products.index', compact('products'));
    }

    /**
     * Store a newly created product.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $product = Product::create($validated);

        return redirect()->route('products.show', $product)
            ->with('success', 'Product created successfully.');
    }
}
```

**Guidelines:**
- Use 4 spaces for indentation
- Maximum line length: 120 characters
- Use type hints for method parameters and returns
- Write docblocks for all public methods
- Use camelCase for variable and method names
- Use UPPERCASE for constants

### JavaScript/TypeScript

Follow **ESLint** configuration in project:

```javascript
// ✅ Good
const getUserName = (userId) => {
  return fetch(`/api/users/${userId}`)
    .then(response => response.json())
    .catch(error => console.error('Error:', error));
};

// ❌ Bad
const getusername = userid => fetch(`/api/users/${userid}`).then(r => r.json());
```

**Guidelines:**
- Use semicolons
- Use single quotes for strings
- Use const/let, avoid var
- Use arrow functions when appropriate
- Add comments for complex logic

### Python/FastAPI

Follow **PEP 8** style guide:

```python
"""Module for handling API forecasting endpoints."""

from fastapi import FastAPI
from pydantic import BaseModel
import joblib


class ForecastRequest(BaseModel):
    """Data model for forecast API requests."""
    
    store_id: str
    product_id: str
    unit_price: float
    bulan: int
    tahun: int


def load_model(model_path: str):
    """
    Load machine learning model from file.
    
    Args:
        model_path (str): Path to model file
        
    Returns:
        object: Loaded model
        
    Raises:
        FileNotFoundError: If model file not found
    """
    return joblib.load(model_path)
```

**Guidelines:**
- Maximum line length: 88 characters (Black formatter)
- Use 4 spaces for indentation
- Write docstrings for all functions and classes
- Use snake_case for function and variable names
- Use type hints for better code clarity

## Commit Message Convention

Follow **Conventional Commits** format:

```
<type>(<scope>): <subject>

<body>

<footer>
```

### Types

- **feat**: A new feature
- **fix**: A bug fix
- **docs**: Documentation changes
- **style**: Code style changes (formatting, missing semicolons, etc.)
- **refactor**: Code refactoring without feature changes
- **perf**: Performance improvements
- **test**: Adding or updating tests
- **chore**: Dependency updates, build changes

### Examples

```bash
# Feature
git commit -m "feat(forecast): add monthly prediction API endpoint"

# Bug fix
git commit -m "fix(dashboard): correct sales calculation formula"

# Documentation
git commit -m "docs(readme): update installation instructions"

# Refactoring
git commit -m "refactor(models): simplify transaction model relationships"

# With detailed message
git commit -m "feat(inventory): add automatic stock recommendation

- Implemented ML-based stock level prediction
- Added alerts for low inventory
- Integrated with dashboard for real-time updates

Closes #123"
```

## Submitting Changes

### Before Submitting

1. **Test your changes locally**
   ```bash
   # Run tests
   cd Laravel && php artisan test
   
   # Check code style
   php artisan pint
   
   # Lint JavaScript
   npm run lint
   ```

2. **Update documentation**
   - Update README if adding features
   - Add API documentation if creating endpoints
   - Update setup instructions if needed

3. **Keep commits clean**
   ```bash
   # Rebase and squash if needed
   git rebase -i upstream/main
   ```

4. **Sync with upstream**
   ```bash
   git fetch upstream
   git rebase upstream/main
   ```

### Creating a Pull Request

1. **Push to your fork**
   ```bash
   git push origin feature/your-feature-name
   ```

2. **Create Pull Request on GitHub**
   - Click "New Pull Request" button
   - Compare across forks
   - Select your feature branch

3. **Fill PR Template**
   ```markdown
   ## Description
   Brief description of your changes

   ## Type of Change
   - [ ] Bug fix
   - [ ] New feature
   - [ ] Breaking change
   - [ ] Documentation update

   ## Related Issues
   Closes #issue_number

   ## Testing
   Describe how to test your changes

   ## Checklist
   - [ ] Code follows project style
   - [ ] Comments added for complex logic
   - [ ] Documentation updated
   - [ ] No breaking changes
   ```

## Pull Request Process

1. **Code Review**: Team members will review your code
2. **Address Feedback**: Update code based on review comments
3. **Approval**: Pull request must be approved by at least one maintainer
4. **Merge**: PR will be merged into main branch

### Review Expectations

Be prepared for constructive feedback on:
- Code quality and clarity
- Performance implications
- Security considerations
- Test coverage
- Documentation completeness

---

## Reporting Issues

### Before Creating an Issue

- Check existing issues (open and closed)
- Search discussion forums
- Check documentation for known solutions

### Creating an Issue

Click "New Issue" and fill out the template:

```markdown
## Description
Clear description of the issue

## Steps to Reproduce
1. First step
2. Second step
3. Etc.

## Expected Behavior
What should happen

## Actual Behavior
What actually happened

## Environment
- OS: (e.g., Windows 10)
- PHP Version: 8.3
- Browser: Chrome 120
- Other relevant info

## Screenshots
If applicable, add screenshots

## Additional Context
Any other context
```

## Feature Requests

### Process

1. **Check existing requests**: Avoid duplicates
2. **Create detailed request**: Explain use case and benefits
3. **Discuss with maintainers**: Get feedback before implementing

### Template

```markdown
## Feature Description
Clear description of the feature

## Use Case
Why this feature is needed

## Proposed Solution
How you think it should work

## Alternative Solutions
Other possible approaches

## Additional Context
Any other relevant information
```

---

## Questions?

- 💬 Join [GitHub Discussions](https://github.com/smartbranch-bi/discussions)
- 📧 Email: smartbranch.support@example.com
- 📱 Create an issue with "question" label

---

**Thank you for contributing to SmartBranch BI! 🎉**

Your contributions help make this project better for everyone!
