# tasca_s4_01 - Integrated Order System (SERRA)

**Description**: Laravel-based web application for managing an automated online shopping cart for carpentry products. The application has a chameleon design that transitions transparently between a public (anonymous) and private (authenticated) environment, ensuring the persistence of the current order and managing the complete life cycle of the user and their billing history.

---

## Technologies

### Frontend
- **Template Engine**: Blade (Laravel Template Engine) with inherited structures, control directives (`@extends`, `@section`, `@foreach`, `@forelse`) and global variable injection.
- **Client Logic**: Native Asynchronous JavaScript (`XMLHttpRequest` interface / POST requests behind the scenes).
- **Client Security**: Native injection of CSRF anti-forgery tokens in asynchronous headers (`X-CSRF-TOKEN`) and production forms.
- **Styles and Design**: CSS grid structure wrapped in a dynamic corporate layout with visual transition classes.

### Backend
- **Programming Language**: PHP (Object Oriented, typed parameters, namespaces and strict conditional structures).
- **Main Framework**: Laravel (MVC architecture with dynamic controller navigation and global response management in HTML Blade and JSON formats).
- **Controllers**:
- `AuthController`: Industrial session management, credential validation and security token regeneration.
- `CatalogueController`: Processing of mapped dynamic slugs to load associated collections from the database.
- `OrderController`: Asynchronous cart event control, switch-case business logic execution for unit-of-measure pricing (unit, m, m², and m³) and order persistence.
- `ProfileController`: Atomic form processing and encapsulation of business and privacy strategies.
- **Security and Access**: `Auth::attempt()` debugging mechanism, browser session control middleware, form request validation, and session lockout protection.
- **Data Mapping (ORM)**: Eloquent ORM with complex relation method definition (`belongsTo`, `hasMany`, `hasOne`, `belongsToMany`), mass-assignable property encapsulation (`$fillable`), disabling timestamps and automated model lifecycle events (`booted / creating`) for unique sequential code generation.

### Database and Persistence
- **Data Engine**: MariaDB / MySQL.
- **Structure Management (Migrations)**: Fully normalized table architecture linked by foreign keys with integrity constraints (`constrained`) and combined unique indexes, distributing information into 14 functional entities.
- **Robustness Mechanism**: Atomic database transactions (`DB::transaction`) to shield concurrent multi-table inserts and avoid record corruption in the processes of profile registration and controlled deletion of users with tax history.
- **Data Feed (Seeders)**:
- `DatabaseSeeder`: Initial structure for users and test environments.
- `BotDataSeeder`: Automated processor that parses raw JSON data files, applies complex regular expressions (`preg_match`) to extract and clean technical dimensions (diameters, lengths, widths and heights) and calculates prices and orders dynamically.

---

## Setup and Execution Instructions

Follow these sequential steps to clone, configure, and run the application locally:

### 1. Clone the Repository and Check Out the Branch
Open your terminal (Git Bash recommended) and execute:
```bash
git clone https://github.com/alangiralt-dot/tasca_s4_01.git && cd tasca_s4_01 && git checkout develop
```

### 2. Install Project Dependencies
Generate the local `vendor` folder by downloading all the required backend packages:
```bash
composer install
```

### 3. Initialize the Environment File
Create your local environment configuration file from the repository template:
```bash
cp .env.example .env
```

### 4. Generate the Application Encryption Key
Assign the unique security key required by Laravel to run the application:
```bash
php artisan key:generate
```

### 5. Create and Configure the Database
1. Open your browser, access **phpMyAdmin** (`http://localhost/phpmyadmin`), and create a new empty database named **`serra`**.
2. Open your freshly created **`.env`** file and verify that the database configuration blocks match your standard environment parameters:
```ini
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=serra
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Link the Public Storage Folder
Generate the clean system symbolic link shortcut to map the catalog images into the public folder seamlessly:
```bash
php artisan storage:link
```

### 7. Run Database Migrations and Seeders
Generate the complete table architecture and populate the database with all default setup data:
```bash
php artisan migrate:fresh --seed && php artisan db:seed --class=BotDataSeeder
```

---

## Documentation Structure

All technical documentation for the project is centralized in the **`docs/`** folder in the root of the repository:

- **Entity-Relationship Model (ERM)**: The updated diagram of the MariaDB database with the 14 functional entities.
- **Use Cases**: The collection of screenshots ordered from `UC-00` to `UC-10` that visually demonstrate the actual operation of the application.

---

## Use Cases

### UC-00: System Initialization and Root Access

*   **Actor**: Guest Client (Anonymous).
*   **Goal**: Access the application for the first time, establishing a clean shopping environment and landing automatically on the active cart interface.
*   **Preconditions**: None (cold access via browser address bar).
*   **Main Success Scenario**:
    1. The actor enters the root URL in the browser: `localhost/alan/SERRA/public/`.
    2. The Laravel router (`web.php`) intercepts the request and handles the redirection to the current order route (`orders.showOrderDetails.current`).
    3. The `OrderController` detects that there is no active cart payload or previous data stored in the current session.
    4. The system initializes a clean state in the backend session:
        * Sets the current timestamp dynamically.
        * Assigns a placeholder code (`-`).
        * Forces the order status strictly to **"En curs"**.
        * Sets the taxable base, taxes (IVA 21%), and final amount to **0,00 €**.
    5. The Blade template engine compiles the layout in **Public Mode**.
    6. The application displays the corporate user interface showing the empty state message: *"No hi ha cap producte carregat en aquesta comanda."*
    7. The sidebar renders camoleonically: keeping *"El meu perfil"* as the clicable element for registration in public mode, and displaying the fallback text **"Login"** at the bottom.

*   **Alternative Flows**: None.

#### Attached Visual Reference:
*   `[Image: UC-00_system_initialization.png]`: A real screenshot of the Chrome browser displaying the empty current order view with all structural indicators set to zero.
### UC-01: Browse Product Catalogue

*   **Actor**: Guest Client (Anonymous) / Authenticated User.
*   **Goal**: Navigate through the product categories and view detailed technical variations (references, measurements, availability, and pricing).
*   **Preconditions**: None (accessible in both public and private sessions).
*   **Main Success Scenario**:
    1. The actor interacts with the sidebar category dropdown menu (e.g., clicking on "Fustes mecanitzades").
    2. The actor selects a specific timber product line (e.g., "Pals rodons de fusta a l'autoclau").
    3. The Laravel routing engine processes the dynamic slug through the CatalogueController.
    4. The system queries MariaDB to retrieve all available dimensions and specific data for that product family.
    5. The application renders a structured data grid displaying the father product image and name, and the child product technical specifications.
    6. The actor reviews the precise technical specifications: internal reference, diameter/length measurements, shipping availability (24/48h), and unit price (€/unit).

*   **Alternative Flows**: None.

#### Attached Visual Reference:
*   [Image: UC-01_browse_product_catalogue.png]: A real screenshot of the Chrome browser displaying the father and child products grid for autoclaved round timber poles, showing technical specifications and the sidebar in public mode.

### UC-02: Manage Items in Current Order (Public Mode)

*   **Actor**: Guest Client (Anonymous).
*   **Goal**: Add child products variants to the temporary session cart from the catalogue view without reloading the webpage.
*   **Preconditions**: The system has been successfully initialized (UC-00) and the actor is browsing the product grid within a category view.
*   **Main Success Scenario**:
    1. The actor adjusts the desired quantity for a specific child product reference using the plus/minus stepper interface.
    2. The actor clicks the yellow "AFEGIR" button on the item row.
    3. The application triggers an asynchronous JavaScript XMLHttpRequest (POST) targeting the /orders/add endpoint.
    4. The request safely injects the mandatory anti-forgery CSRF token and passes the product_id and quantity as parameters.
    5. The OrderController validates that both inputs are clean integers, fetches the multidimensional "current_order" session array, and updates or initializes the key-value map.
    6. Upon receiving a successful 200 JSON response, the frontend swaps the row styles dynamically, displaying a green success banner reading: "El producte s'ha afegit correctament a la comanda actual".
    7. A 4-second JavaScript timeout automatically restores the original table row layout.

*   **Alternative Flows**: None.

#### Attached Visual Reference:
*   [Image: UC-02_manage_items_public_cart.png]: A real screenshot of the catalogue table view showcasing the temporary green confirmation banner on an item row immediately after pressing the add button.


### UC-03: View Current Order Items (Public Mode)

*   **Actor**: Guest Client (Anonymous).
*   **Goal**: Review the detailed itemization, quantity calculations, applied taxes, and totals of all products added during the anonymous browsing session.
*   **Preconditions**: The actor has added at least one product variation to the session cart (UC-02).
*   **Main Success Scenario**:
    1. The actor clicks on the cart icon or navigates directly to the current order path: localhost/alan/SERRA/public/comandes/current.
    2. The routing engine passes the request to the showOrderDetails method inside the OrderController, detecting the "current" parameter string.
    3. The controller extracts the "current_order" multi-dimensional array from the backend PHP session and queries MariaDB to pull the product specifications.
    4. The system iterates through the loaded entries, executing a dynamic switch-case statement based on the product unit_id to compute line subtotals according to measurements.
    5. The controller rounds the numerical data, calculates the global 21% IVA tax amount, stores the final values back into the session data, and returns the invoice Blade template.
    6. The application renders the corporate data grid showcasing the product variation rows with the empty trash bin action icon.
    7. The system populates the dynamic billing overview display block: code reads as a hyphen ("-"), status shows strictly as "En curs", subtotal marks "79,00 €", tax states "16,59 €", and final amount equals "95,59 €".
    8. The layout compiles the page under Public Mode parameters, keeping "El meu perfil" open on the sidebar and completely hiding the order confirmation action.

*   **Alternative Flows**: None.

#### Attached Visual Reference:
*   [Image: UC-03_view_current_order_public.png]: A real screenshot of the current order route in public mode showing item lines, calculated totals boxes, an unauthenticated sidebar structure, and an absent confirmation action trigger.
### UC-04: Modify Current Order Items (Public Mode)

*   **Actor**: Guest Client (Anonymous).
*   **Goal**: Adjust item quantities or completely remove product variations from the active shopping cart view while remaining unauthenticated.
*   **Preconditions**: The system has been successfully initialized (UC-00), the actor has added items to the session (UC-02), and is currently viewing the active cart interface (UC-03).
*   **Main Success Scenario**:
    1. The actor clicks the minus (-) or plus (+) stepper interface buttons on a specific product variation row to alter the desired unit volume.
    2. The application intercepts the click event and submits a POST request containing the unique product_id and the modified quantity to the OrderController endpoints.
    3. The controller processes the request parameters, validates that the inputs are clean integers, and updates the specific quantity field inside the current_order multidimensional session array.
    4. The system automatically triggers an internal business logic recalculation, updating the row subtotal according to the technical product line dimensions.
    5. The controller feeds the updated subtotal into the dynamic tax calculation mechanism to compute the global 21% IVA amount.
    6. The backend session saves the updated state and re-renders the current invoice view to display the modified figures across the base imposable, tax amount, and final total boxes in real-time.
    7. The interface preserves all Public Mode behaviors, keeping the "El meu perfil" shortcut open and ensuring the order confirmation action remains hidden.

*   **Alternative Flows**:
    *   **A) Total product row erasure via the trash bin icon**:
        1. The actor clicks the grey trash icon located on the far right section of the targeted product row.
        2. The application fires a removal request to erase that specific product ID key from the session array dataset.
        3. The system clears the product entry from the visual grid list and subtracts all associated costs from the dynamic billing overview display block.
        4. If the cart layout becomes completely empty, the application falls back dynamically to the empty state screen, displaying the unauthenticated greeting and the empty state alert message.

#### Attached Visual Reference:
*   [Image: UC-04_modify_current_order_public.png]: A real screenshot of the current order view in public mode showing updated product quantities, altered financial calculation summaries, and an unauthenticated sidebar workspace.
### UC-05: User Registration and Session Transition

*   **Actor**: Guest Client (Anonymous).
*   **Goal**: Create a customer profile and establish secure access credentials while seamlessly preserving all items collected inside the temporary session order.
*   **Preconditions**: The system has been successfully initialized (UC-00) and the actor clicks on the "El meu perfil" link from the public sidebar layout.
*   **Main Success Scenario**:
    1. The system detects the unauthenticated status and renders an empty profile form displaying the dynamic header: "El meu perfil" and a yellow action button reading "REGISTRAR-SE".
    2. The actor inputs their personal and shipping information into the dedicated fields: street address, number, floor, door, postal code, city name, and province name.
    3. The actor provides their structural credentials by entering a unique email address and a secure password.
    4. The actor clicks the yellow "REGISTRAR-SE" submit button.
    5. The application triggers a validated POST request to the /registrar-se route, redirecting the payload directly to the ProfileController store method.
    6. The controller processes the data, runs a firstOrCreate lookup on provinces and cities tables to link the geographic IDs, and executes an atomic database transaction.
    7. The system creates the customer record first, then generates the user record setting the name to null, hashing the password string, and anchoring the customer_id pointer.
    8. The framework automatically authenticates the user via Auth::login() and triggers a redirection route to the current order view.
    9. The browser loads the /comandes/current view under Private Mode parameters, keeping all previous cart items intact and unlocking the yellow confirmation actions.

*   **Alternative Flows**: None.

#### Attached Visual Reference:
*   [Image: UC-05_user_registration.png]: A real screenshot of the unpopulated profile registration view displaying the specialized email/password inputs, the unauthenticated sidebar structure, and the customized yellow "REGISTRAR-SE" button.
### UC-06: User Login and Session Transition

*   **Actor**: Guest Client (Anonymous).
*   **Goal**: Authenticate into the application using an existing account while seamlessly preserving all items collected inside the temporary session order.
*   **Preconditions**: The system has been successfully initialized (UC-00) and the actor clicks on the "Login" link from the public sidebar layout.
*   **Main Success Scenario**:
    1. The system renders the unauthenticated login view displaying the header "Login" and the credential boxes.
    2. The actor inputs their registered email address and secure password into the corresponding form fields.
    3. The actor clicks the yellow "OBRIR SESSIÓ" submit button.
    4. The application triggers a standard POST request targeting the /login endpoint, managed by the AuthController.
    5. The controller validates the credentials against the users table records in MariaDB using the framework's native attempt mechanism.
    6. Upon successful match, the system regenerates the session ID to prevent fixation vulnerabilities but completely retains the multi-dimensional "current_order" cart array.
    7. The framework automatically logs the user in via Auth::login() and triggers a redirection route to the current order path.
    8. The browser loads the /comandes/current view in Private Mode, displaying the fully preserved cart items and unlocking the yellow confirmation actions.

*   **Alternative Flows**:
    *   **A) Invalid Credentials**:
        1. If the email or password do not match any MariaDB records at step 5, the AuthController blocks authentication.
        2. The controller throws a ValidationException containing the strict error message.
        3. The framework automatically redirects the actor back to the login form view, clearing both input boxes completely and highlighting the email field with a red validation border indicator.

#### Attached Visual Reference:
*   [Image: UC-06_user_login.png]: A real screenshot of the clean login view displaying the specialized email/password inputs, the public sidebar structure, and the customized yellow "OBRIR SESSIÓ" button.

### UC-07: Confirm Current Order

*   **Actor**: Authenticated User.
*   **Goal**: Finalize the checkout process, save the active session cart variables as a formal invoice inside MariaDB, and empty the shopping state.
*   **Preconditions**: The user is successfully authenticated and the active cart view (comandes/current) contains at least one product variation, which unlocks the yellow confirmation trigger header.
*   **Main Success Scenario**:
    1. The actor clicks the yellow "CONFIRMAR COMANDA" button located at the top-right section of the header action bar.
    2. The application triggers a standard POST request targeting the /orders/confirm route.
    3. The OrderController receives the payload, verifies that the "current_order" session array is not empty, and instantiates a new Order database model.
    4. The system automatically retrieves the logged-in customer identity via Auth::user()->customer_id and injects the global transaction details (status_id = 1, order_availability, total_amount).
    5. The Order model booted sequence automatically computes and sets the standardized unique sequence code string (e.g., SERRA-2026-00001).
    6. The controller executes a loop through the session data, linking each product variant entry to the child_product_order pivot table, logging the specific quantity volume, price factors, and subtotals.
    7. The system flushes the backend memory completely by forgetting the current_order, order_availability, current_amount, and current_date session keys.
    8. The application triggers a secure redirection routing the actor straight to the general historical summary dashboard view (/comandes).

*   **Alternative Flows**: None.

#### Attached Visual Reference:
*   [Image: UC-07-A_confirm_order_trigger.png]: A real screenshot of the current order view under Private Mode parameters showcasing the active product item lines, the rounded financial balance card displays, and the fully unlocked yellow "CONFIRMAR COMANDA" header button.

### UC-08: View Confirmed Order Details

*   **Actor**: Authenticated User.
*   **Goal**: Review the frozen technical specifications, unique measurements, and stored historical balances of a specific previously confirmed order.
*   **Preconditions**: The user is successfully authenticated, has historical records inside the orders table, and clicks a specific order details link.
*   **Main Success Scenario**:
    1. The actor requests a specific historical record path via the browser (e.g., clicking on order ID 3: localhost/alan/SERRA/public/comandes/3).
    2. The routing engine routes the GET request parameters straight to the showOrderDetails method inside the OrderController.
    3. The controller executes an eager-loaded database lookup using Order::with(['childProducts.fatherProduct', 'childProducts.unit', 'childProducts.availability', 'status'])->findOrFail($id).
    4. The system queries MariaDB to pull the global order header attributes along with all technical data fields mapped across child_products and the child_product_order pivot table.
    5. The controller extracts the static values (code, status, date) and processes the financial data by calling the sum mechanism on the pivot dataset fields: $order->childProducts->sum('pivot.subtotal').
    6. The controller rounds the numerical data to compute the global taxableBasis, multiplies it by 0.21 to isolate the exact rounded tax amount, and extracts the total historical amount from the order record.
    7. The application returns the invoice Blade template view, injecting the processed database models and all compiled financial variables into the layout.
    8. The browser renders the interface under Private Mode parameters, displaying the precise historical item lines, unique code SERRA-2026-00003, date 03/07/2026 11:15, base imposable 486,86 €, IVA 102,24 €, and the final total display card marking 589,10 €.

*   **Alternative Flows**: None.

#### Attached Visual Reference:
*   [Image: UC-08-B_order_details_view.png]: A real screenshot of the specific order details route (/comandes/3) in private mode showcasing historical item rows, computed units per size, and the locked pricing statistics structure.
### UC-09: Update Profile Data (U of CRUD)

*   **Actor**: Authenticated User.
*   **Goal**: Modify personal profile data fields and shipping instructions inside MariaDB while maintaining the private session state.
*   **Preconditions**: The user is successfully authenticated and is currently viewing the active profile maintenance view: localhost/alan/SERRA/public/el-meu-perfil.
*   **Main Success Scenario**:
    1. The actor clicks on the "El meu perfil" link from the private sidebar workspace menu.
    2. The routing engine handles the request, and the ProfileController retrieves the authenticated customer row along with its associated city and province fields from MariaDB.
    3. The application populates the form inputs with the stored profile metadata (e.g., telephone 972230674, street address, number 18, floor, door, postal code 17190, city Salt, and province Girona).
    4. The actor edits the necessary text data fields within the form layout wrapper.
    5. The actor clicks the yellow "MODIFICAR PERFIL" submit button to trigger a standard POST request targeting the /el-meu-perfil endpoint.
    6. The ProfileController handles the inputs using trim() and executes a firstOrCreate lookup on provinces and cities tables to link the corresponding geographic IDs.
    7. The controller calls the $customer->update() method using the authenticated customer_id pointer to save the changes inside MariaDB.
    8. The browser refreshes to flash a success state indicator, keeping the user in Private Mode with the updated parameters safely displayed.

*   **Alternative Flows**: None.

#### Attached Visual Reference:
*   [Image: UC-09-A_profile_modification_view.png]: A real screenshot of the "El meu perfil" workspace view in private mode displaying populated input boxes, an authenticated sidebar menu layout, and the yellow "MODIFICAR PERFIL" action trigger button.


### UC-10: Terminate Account (D of CRUD)

*   **Actor**: Authenticated User.
*   **Goal**: Request a permanent account termination, ensuring the absolute removal of web credentials while strictly preserving historical records for fiscal auditing.
*   **Preconditions**: The user is successfully authenticated and is currently viewing the active profile maintenance view: localhost/alan/SERRA/public/el-meu-perfil.
*   **Main Success Scenario**:
    1. The actor clicks the yellow "DONAR-SE DE BAIXA" action button at the bottom of the profile maintenance form.
    2. The application intercepts the click event via JavaScript and submits the hidden delete-profile-form using the specialized DELETE method and a secure CSRF token.
    3. The routing engine routes the payload directly to the destroy method inside the ProfileController.
    4. The controller starts an atomic DB::transaction block and runs a condition using Order::where('customer_id', $customerId)->exists() to check the billing records.
    5. If the customer record has zero orders, the system deletes both rows in bulk.
    6. If the customer has confirmed orders, the system deletes exclusively the credentials row from the users table, keeping the customer table entry frozen for structural auditing.
    7. The framework triggers an Auth::logout() sequence, invalidates the session keys, and clears the browser cache parameters.
    8. The application shifts back to Public Mode parameters and redirects the browser straight to the empty cart interface layout (/comandes/current).

*   **Alternative Flows**: None.

#### Attached Visual Reference:
*   [Image: UC-10-A_account_termination_trigger.png]: A real screenshot of the profile layout view highlighting the "DONAR-SE DE BAIXA" button placed next to the modification features under the private workspace layout.
