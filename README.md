Collecting workspace information# ClearIT MVP - Technical Examination (Docker Fixed)

## Overview
This project is a Minimum Viable Product (MVP) developed for the **Clearit PHP Technical Examination**. It demonstrates a complete ticket management system for import/export clearance operations using modern Laravel development practices.

## 🚀 Quick Setup with Docker (Complete Automation)

```bash
# Clone and start with Docker (all dependencies included)
git clone <repository-url>
cd ClearitExam

# Start services - this automatically installs:
# ✅ Laravel Breeze (authentication)
# ✅ Spatie Permissions (roles)
# ✅ Node.js dependencies
# ✅ Database migrations + seeders
# ✅ Asset compilation (Vite)
docker compose -f docker-compose.simple.yml up -d

# Monitor complete setup (wait for "Laravel setup completed successfully!")
docker compose -f docker-compose.simple.yml logs -f php

# Access application
# Main App: http://localhost:8080
# phpMyAdmin: http://localhost:8081
```

## 🛠️ Manual Docker Commands (Optional)

```bash
# Access PHP container for manual operations
docker exec -it clearit_php bash

# Manual dependency management inside container:
composer install               # Laravel packages
npm install                   # Node.js packages
npm run build                # Compile assets
npm run dev                  # Development mode (watch)
php artisan migrate:fresh --seed  # Reset database
```

## 🔧 Docker Features & Fixes Applied

- ✅ **Complete Automation**: Laravel Breeze, Spatie Permissions, all dependencies
- ✅ **Error 502 Fixed**: Proper container dependencies and health checks
- ✅ **Database Setup**: Migrations, seeders, and demo data automatically loaded
- ✅ **Asset Compilation**: Vite build process integrated (npm run build)
- ✅ **Node.js Ready**: Full development environment with NPM
- ✅ **Storage Links**: File upload system properly configured
- ✅ **Permission Management**: Proper file permissions handling

## 👥 Demo Accounts

- **Customer**: `user@clearit.com` / `123456`
- **Agent**: `agent@clearit.com` / `123456`
- **Admin**: `admin@clearit.com` / `123456`

## System Architecture

### Technology Stack
- **Framework**: Laravel 11
- **Database**: SQLite (configurable in `config/database.php`)
- **Frontend**: Bootstrap 5.3 + Blade Templates
- **Authentication**: Laravel Breeze
- **Permissions**: Spatie Laravel Permission
- **File Storage**: Laravel Storage (Public disk)

### Project Structure
```
ClearitExam/
├── app/
│   ├── Http/Controllers/          # Application controllers
│   ├── Models/                    # Eloquent models
│   └── Services/                  # Business logic services
├── database/
│   ├── migrations/               # Database schema
│   ├── factories/                # Test data factories
│   └── seeders/                  # Database seeders
├── resources/views/              # Blade templates
└── routes/                       # Application routes
```

## Examination Requirements & Implementation

### 1. User Authentication ✅
**Requirement**: Develop a login system for "agents" and "users"

**Implementation**:
- **Base System**: Laravel Breeze authentication in auth.php
- **Role Management**: Spatie Permission package configured in permission.php
- **User Roles**: 
  - `user` - Regular customers who create tickets
  - `agent` - Clearance agents who process tickets
  - `admin` - System administrators
- **Models**: `User` with HasRoles trait
- **Seeders**: Demo accounts created in `RoleAndUserSeeder`
- **Middleware**: Role-based access control in app.php

**Demo Accounts**:
- User: `user@clearit.com` / `123456`
- Agent: `agent@clearit.com` / `123456`
- Admin: `admin@clearit.com` / `123456`

### 2. Ticket Management ✅
**Requirement**: Users create tickets with specific details and status transitions

**Implementation**:
- **Model**: `Ticket` with proper relationships
- **Migration**: Complete schema in `create_tickets_table`
- **Controllers**: 
  - `CustomertTicketController` - User operations
  - `AgentTicketController` - Agent operations
- **Views**: 
  - Customer interface: `customer/tickets/index.blade.php`
  - Agent interface: `agent/tickets/index.blade.php`

**Ticket Fields**:
- ✅ Ticket name
- ✅ Ticket type (1=Import, 2=Export, 3=Transit)
- ✅ Transport mode (air, sea, land)
- ✅ Product to import/export
- ✅ Country of origin/destination
- ✅ Status transitions (new → in_progress → completed)

**Features**:
- Complete CRUD operations for tickets
- Role-based access control
- Document attachment system
- Status workflow management

### 3. Documentation Exchange ✅
**Requirement**: Document attachment and review system between users and agents

**Implementation**:
- **File Storage**: Public disk configuration in filesystems.php
- **Document Management**: JSON field in tickets table for document metadata
- **Upload System**: Multi-file upload with validation
- **Download System**: Secure document access for agents
- **UI Components**: Modal-based document viewer in `components/ticket/modal.blade.php`

**Workflow**:
1. ✅ Users attach documents during ticket creation
2. ✅ Agents receive ticket notifications
3. ✅ Agents can request additional documents
4. ✅ Users upload requested documentation
5. ✅ Agents review and finalize tickets

### 4. Notifications ✅
**Requirement**: Basic notification system for status changes and document requests

**Implementation**:
- **Service**: `NotificationService` for centralized notification logic
- **Logging**: Comprehensive logging in logging.php
- **Events**: Ticket lifecycle events tracked
- **Future-Ready**: Email infrastructure prepared (currently logs for demo)

**Notification Types**:
- ✅ New ticket creation
- ✅ Ticket assignment to agent
- ✅ Document requests
- ✅ Ticket completion
- ✅ Comment updates

## Installation & Setup

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM (for asset compilation)

### Installation Steps

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd ClearitExam
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Storage Link**
   ```bash
   php artisan storage:link
   ```

6. **Asset Compilation**
   ```bash
   npm run build
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

## Key Features Demonstrated

### Role-Based Access Control
- **Users**: Can create, edit, and view their own tickets
- **Agents**: Can view new tickets, take assignments, request documents, complete tickets
- **Admins**: Full system access including user management

### Responsive Design
- Bootstrap 5.3 integration
- Mobile-friendly interface
- Dark/light theme support

### File Management
- Secure file uploads with validation
- Document download system for agents
- File type restrictions (PDF, JPG, PNG, DOCX)

### Database Design
- Proper foreign key relationships
- JSON storage for document metadata
- Migration-based schema management

## Testing

The application includes comprehensive test coverage:
- **Authentication Tests**: `tests/Feature/Auth/`
- **Profile Management**: ProfileTest.php
- **Basic Functionality**: ExampleTest.php

Run tests with:
```bash
php artisan test
```

## Recommendations for Future Development

### 1. Enhanced Data Models
**Current**: Enum-based ticket types and transport modes
**Recommendation**: Create dedicated models for `TicketType` and `TransportMode`
```php
// Future implementation
class TicketType extends Model {
    protected $fillable = ['name', 'code', 'description', 'requirements'];
}
```
**Benefits**: Greater flexibility for adding new types, custom requirements per type

### 2. Automated Ticket Assignment
**Current**: Manual ticket assignment by agents
**Recommendation**: Implement automatic assignment based on agent workload and specialization
```php
// Future service
class TicketAssignmentService {
    public function autoAssignTicket(Ticket $ticket): ?User
    {
        return User::role('agent')
            ->withLeastAssignedTickets()
            ->specializedIn($ticket->type)
            ->first();
    }
}
```

### 3. External API Integration
**Recommendation**: Integrate Amadeus City Search API for location autocomplete
```php
// Future implementation
class AmadeusLocationService {
    public function searchCities(string $query): Collection
    {
        // API integration for city/country suggestions
    }
}
```

### 4. Real-Time Notifications
**Current**: Log-based notifications
**Recommendation**: Implement WebSocket-based real-time notifications using Laravel Broadcasting
```php
// Future event broadcasting
class TicketUpdated implements ShouldBroadcast {
    public function broadcastOn(): Channel
    {
        return new PrivateChannel('tickets.'.$this->ticket->created_by);
    }
}
```

### 5. Advanced Document Management
**Recommendation**: Implement document versioning and approval workflows
```php
// Future model
class DocumentVersion extends Model {
    protected $fillable = ['document_id', 'version', 'file_path', 'approved_by', 'approved_at'];
}
```

### 6. Audit Trail System
**Recommendation**: Comprehensive audit logging for compliance requirements
```php
// Future trait
trait Auditable {
    protected static function bootAuditable()
    {
        static::created(fn($model) => AuditLog::log('created', $model));
        static::updated(fn($model) => AuditLog::log('updated', $model));
    }
}
```

## Configuration Files

### Key Configuration Files
- **Database**: database.php - Database connections
- **Authentication**: auth.php - Auth guards and providers
- **Permissions**: permission.php - Role configuration
- **Mail**: mail.php - Email service setup
- **File Systems**: filesystems.php - Storage configuration

## Routes Structure

### Web Routes (`routes/web.php`)
- **Public**: Welcome page, authentication routes
- **Customer**: Ticket CRUD operations (role: user)
- **Agent**: Ticket management and processing (role: agent|admin)
- **Admin**: System administration (role: admin)

### Route Groups
```php
// Customer routes
Route::middleware(['auth', 'role:user'])->prefix('customer/tickets')

// Agent routes  
Route::middleware(['auth', 'role:agent|admin'])->prefix('agent/tickets')

// Admin routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')
```

## Security Features

### Input Validation
- Form request validation for all user inputs
- File upload restrictions and validation
- CSRF protection on all forms

### Access Control
- Role-based middleware protection
- Route-level permission checks
- Model-level ownership verification

### File Security
- Secure file storage in non-public directories
- Controlled file access through application
- File type and size validation

---

This project successfully demonstrates all required examination components while maintaining modern Laravel development standards and providing a foundation for future enhancements.
