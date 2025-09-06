# Plan Modals Component Usage Guide

## Overview
The `plan-modals.blade.php` component provides a reusable set of three modals for plan selection, payment processing, and success confirmation. This component can be included in any plan detail page to provide a consistent user experience.

## File Location
```
resources/views/components/plan-modals.blade.php
```

## Usage

### Basic Usage
```php
@include('components.plan-modals')
```

### With Data
```php
@include('components.plan-modals', [
    'plans' => $plans,
    'userEmail' => auth()->user()->email
])
```

## Data Structure

### Plans Array
The component expects a `$plans` array with the following structure:

```php
$plans = [
    [
        'id' => 1,
        'name' => 'Training Nutrition Plan',
        'description' => 'Optimise your training gains by eating with purpose...',
        'one_time_price' => 'A$249',
        'monthly_price' => 'A$34.58',
        'duration_months' => 8,
        'popular' => false,
        'info_link_text' => 'What\'s included'
    ],
    [
        'id' => 2,
        'name' => 'Power Play',
        'description' => 'Training nutrition plan PLUS 30 min Consult...',
        'one_time_price' => 'A$369',
        'monthly_price' => 'A$51.25',
        'duration_months' => 8,
        'popular' => true,
        'info_link_text' => 'About Consultations'
    ],
    [
        'id' => 3,
        'name' => 'Game Plan',
        'description' => 'Training nutrition Plan PLUS 60 min Consult...',
        'one_time_price' => 'A$449',
        'monthly_price' => 'A$62.36',
        'duration_months' => 8,
        'popular' => false,
        'info_link_text' => 'What\'s in a 1 on 1'
    ]
];
```

### Required Variables
- `$plans` (array): Array of plan data (optional - defaults provided)
- `$userEmail` (string): User's email address (optional - defaults to 'user@example.com')

## Features

### 1. Plan Choose Modal
- Toggle between one-time payment and monthly plans
- Dynamic plan rendering based on provided data
- Popular badge support
- Responsive design

### 2. Payment Modal
- Dynamic plan information display
- Coupon code functionality
- Credit card form
- Form validation
- Terms and conditions

### 3. Congrats Modal
- Success confirmation
- Dynamic plan information
- Call-to-action button for next steps

## JavaScript Functionality

The component includes JavaScript for:
- Plan toggle functionality
- Dynamic modal content updates
- Coupon code handling
- Form submission (placeholder)
- Modal navigation

## Customization

### Styling
The component uses Bootstrap classes and custom CSS. You can override styles by:
1. Adding custom CSS classes
2. Modifying the component file
3. Using CSS custom properties

### Functionality
To customize functionality:
1. Modify the JavaScript section in the component
2. Add event listeners for custom behavior
3. Integrate with your payment processing system

## Integration Examples

### In a Controller
```php
public function showPlan($id)
{
    $plans = [
        // Your plan data
    ];
    
    $userEmail = auth()->user()->email ?? 'guest@example.com';
    
    return view('plans.detail', compact('plans', 'userEmail'));
}
```

### In a Blade Template
```php
@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Choose Your Plan</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#planChooseModal">
        View Plans
    </button>
</div>

@include('components.plan-modals', ['plans' => $plans, 'userEmail' => $userEmail])
@endsection
```

## API Integration Points

The component includes placeholder functions for:
- Coupon validation
- Payment processing
- Plan selection handling
- Questionnaire redirection

Replace these with your actual API calls:

```javascript
// Example: Coupon validation
applyPromoBtn.addEventListener('click', function() {
    const promoCode = promoCodeInput.value.trim();
    
    fetch('/api/validate-coupon', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ code: promoCode })
    })
    .then(response => response.json())
    .then(data => {
        if (data.valid) {
            promoMessage.textContent = 'Coupon applied successfully!';
            promoMessage.className = 'form-text text-success';
        } else {
            promoMessage.textContent = 'Invalid coupon code';
            promoMessage.className = 'form-text text-danger';
        }
    });
});
```

## Dependencies

- Bootstrap 5 (for modal functionality)
- Laravel Blade templating
- jQuery (optional, for additional functionality)

## Browser Support

- Modern browsers with ES6 support
- Bootstrap 5 compatible browsers
- Mobile responsive design

## Notes

- The component includes default plan data if none is provided
- All modals are responsive and mobile-friendly
- JavaScript is included inline for simplicity but can be extracted to separate files
- Form validation is basic and should be enhanced for production use
