# Consultations Module

This module provides a complete CRUD (Create, Read, Update, Delete) system for managing consultations in the admin panel.

## Features

- **List Consultations**: View all consultations with pagination
- **Create Consultation**: Add new consultations with content, price, and time
- **Edit Consultation**: Modify existing consultation details
- **Delete Consultation**: Remove consultations with confirmation
- **View Consultation**: Detailed view of individual consultations

## Database Structure

The `consultations` table contains the following fields:

- `id` - Primary key (auto-increment)
- `content` - Text field for consultation description
- `price` - Decimal field for consultation price (10,2 precision)
- `time` - Integer field for consultation duration in minutes
- `created_at` - Timestamp for creation date
- `updated_at` - Timestamp for last update

## Files Created

### Models
- `app/Models/Consultation.php` - Eloquent model with proper fillable fields and casting

### Controllers
- `app/Http/Controllers/Admin/ConsultationController.php` - Resource controller with all CRUD operations

### Views
- `resources/views/backend/pages/consultations/index.blade.php` - List all consultations
- `resources/views/backend/pages/consultations/create.blade.php` - Create new consultation form
- `resources/views/backend/pages/consultations/edit.blade.php` - Edit existing consultation form
- `resources/views/backend/pages/consultations/show.blade.php` - View consultation details

### Database
- `database/migrations/2025_09_03_181644_create_consultations_table.php` - Migration file
- `database/seeders/ConsultationSeeder.php` - Sample data seeder

### Routes
- Added to `routes/web.php` under the `backend.` route group

### Navigation
- Added to `resources/views/backend/includes/sidebar.blade.php` with support icon

## Usage

1. **Access the module**: Navigate to Admin Panel → Consultations
2. **View consultations**: See all consultations in a table format
3. **Add consultation**: Click "Add Consultation" button
4. **Edit consultation**: Click the edit icon on any consultation row
5. **Delete consultation**: Click the delete icon (with confirmation)

## Validation Rules

- **Content**: Required, string
- **Price**: Required, numeric, minimum 0
- **Time**: Required, integer, minimum 1

## Sample Data

The seeder includes 5 sample consultations:
1. Initial nutrition consultation ($75, 60 min)
2. Follow-up consultation ($50, 45 min)
3. Sports nutrition consultation ($100, 90 min)
4. Weight management consultation ($65, 60 min)
5. Quick nutrition check-in ($35, 30 min)

## Installation

1. Run the migration: `php artisan migrate`
2. (Optional) Seed sample data: `php artisan db:seed --class=ConsultationSeeder`
3. Access via admin panel navigation
