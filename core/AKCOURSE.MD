You are a senior Laravel engineer and product UI designer working inside an existing Laravel application.

Your task is to rebuild and update the existing AK23 Academy frontend, student dashboard, and administrator dashboard so they match the design specification below.

Work directly in the existing repository. Do not create a disconnected demo application.

==================================================
1. FIRST INSPECT THE EXISTING PROJECT
==================================================

Before changing code:

1. Read the repository instructions and README files.
2. Inspect:
   - composer.json
   - package.json
   - routes/web.php
   - routes/api.php
   - existing controllers
   - models
   - migrations
   - middleware
   - policies
   - Blade, Livewire, Vue, React, or Inertia files
   - existing CSS framework and design tokens
3. Determine whether the project uses:
   - Blade
   - Livewire
   - Inertia
   - Vue
   - React
   - Tailwind CSS
   - Bootstrap
4. Continue using the project’s existing frontend architecture.
5. Do not replace the current stack merely because another stack is more familiar.
6. Preserve existing backend logic, routes, database data, authentication, and user changes.
7. Fix root causes rather than introducing duplicate components or parallel systems.

Use reusable layouts and components rather than repeating markup.

==================================================
2. PRODUCT OVERVIEW
==================================================

The platform is called:

AK23 Academy

It is connected visually to:

AK23 Studio Kits

The application sells downloadable music-production courses with matching licensed software packages.

There are three beginner product families:

1. Vocal Mixing Series
2. Audio Editing Software Series
3. Beat Making Series

Each product family includes:

- Individual course packages
- One complete-series bundle
- Downloadable lessons
- Matching licensed software packages
- Course downloads
- Software installation and activation guidance

The application has three major UI areas:

1. Public storefront
2. Authenticated student dashboard
3. Authenticated administrator dashboard

==================================================
3. AUTHENTICATION AND AUTHORIZATION
==================================================

Use Laravel’s real authentication system.

Do not implement authentication using frontend state, URL parameters, localStorage roles, or hidden buttons.

Required roles:

- student
- admin
- support
- content_manager

Authentication requirements:

- Passwords must use Laravel Hash
- Sessions must be regenerated after login
- Logout must invalidate the session
- CSRF protection must remain enabled
- Login attempts must be rate-limited
- Add account lockout support if the existing application does not already have it
- Password reset should use Laravel’s standard secure flow
- Never store plaintext passwords
- Never trust a user_id supplied by the browser
- Resolve the authenticated user with auth()->user()
- Protect dashboards using middleware and policies

Required route behavior:

- Guests can access the public storefront
- Students can access the student dashboard
- Administrators can access the admin dashboard
- Students must receive a 403 or safe redirect when requesting admin routes
- Administrators should not be authenticated based only on frontend data
- Support and content-manager permissions should be enforced using policies or gates

Suggested middleware:

- auth
- verified where appropriate
- role:student
- role:admin
- role:support
- role:content_manager

Do not allow a role selector on the production login form.

Demo accounts may be created with seeders for local development, but passwords must still be hashed.

Suggested local demo users:

Student:
- student@ak23.demo
- password: demo1234

Administrator:
- admin@ak23.demo
- password: demo1234

Clearly mark these as development-only accounts.

==================================================
4. GLOBAL DESIGN SYSTEM
==================================================

Create reusable design tokens.

Public storefront colors:

- Main background: #09090D
- Secondary background: #0D0D12
- Card background: #141419
- Elevated card background: #191922
- Main text: #F1F1F5
- Muted text: #AAAAB3
- Secondary muted text: #73737D
- Gold: #F0A62F
- Bright gold: #FFBD4B
- Border: #34343D
- Gold border: #59401F
- Success: #0AA05A
- Warning: #E89A0C
- Error: #DF3F3F

Dashboard colors:

- Dashboard background: #F5F7F8
- Card background: #FFFFFF
- Main text: #101417
- Muted text: #6F7A80
- Border: #DFE3E5
- Sidebar background: #06100C
- Active green: #0AA05A
- Dark green: #007A43
- Teal: #159B9B
- Blue: #2C91DA
- Amber: #E89A0C
- Red: #DF3F3F

Typography:

- Use Manrope for bold headings if it is already available or can be added safely
- Use DM Sans for body text if it is already available or can be added safely
- Otherwise use the project’s existing sans-serif fonts
- Major headings should use font weight 800
- Section headings should be bold and easy to scan
- Body copy should remain lighter for hierarchy
- Avoid very small text
- Do not use body text smaller than approximately 12px on desktop
- Interactive controls should generally use 13px or larger

General design principles:

- Modern and clean
- Bold headings
- High contrast
- Simple navigation
- Clear calls to action
- Consistent spacing
- Restrained shadows
- Rounded corners without excessive pill styling
- Clear status badges
- No decorative clutter
- No emojis
- Use the project’s existing icon system or a consistent icon library

==================================================
5. PUBLIC STOREFRONT
==================================================

The public storefront must remain the default homepage.

Do not replace the homepage with the admin dashboard.

Main navigation:

- Vocal Mixing
- Audio Software
- Beat Making
- All Courses
- Login
- Cart

The homepage should use:

- Near-black background
- Gold accents
- Bold hero heading
- Large video preview
- Clear course categories
- Series bundles
- Student-friendly explanations
- Responsive navigation

Login must lead to the real Laravel login page.

Cart must remain visible to public users.

==================================================
6. PUBLIC COURSE CARDS
==================================================

Rebuild the course cards using a modern media-first structure.

Desktop grid:

- Three columns on large screens
- Two columns on tablets
- One column on mobile
- Maximum content width around 1280–1320px
- Gap around 20–24px

Approximate desktop card structure:

- Overall card height: approximately 600–640px
- Border radius: 16px
- Large visual/media section: approximately 260–280px high
- Content section: approximately 190–220px
- Purchase footer: approximately 160–190px
- Consistent card heights across varying descriptions

Card structure:

1. Media area
   - Course screenshot or supplied product visual
   - Course number badge
   - Stage/category badge
   - Preserve screenshot proportions
   - Do not stretch plugin or software screenshots

2. Information area
   - Bold course title
   - Short learning outcome
   - Lesson count
   - Video duration
   - Preview Course action

3. Software and purchase area
   - “Plugin license included” or “Software license included” badge
   - Exact software package
   - License edition where confirmed
   - Total TSH price
   - Purchase button

Card behavior:

- Subtle hover elevation
- Slight visual zoom or depth effect
- Strong visible focus state
- Entire card should remain easy to scan
- Do not hide required package information
- Clamp long descriptions to approximately two or three lines
- Keep price and purchase button aligned at the bottom

Do not copy example course content from unrelated designs.

Use the real AK23 course information.

==================================================
7. PRODUCT FAMILY 1: VOCAL MIXING
==================================================

Courses:

1. Tuned Plugins
   - Stage: Getting Started
   - Outcome: Build your first simple vocal-processing workflow.

2. Cymatics Voxity
   - Stage: Beginner
   - Outcome: Shape vocal tone, dynamics, and space with confidence.

3. Nuro Audio Xvox Pro
   - Stage: Developing Your Skills
   - Outcome: Balance compression, de-essing, tone, and effects.

4. iZotope Nectar 4
   - Stage: Intermediate
   - Outcome: Develop a more detailed vocal-processing workflow.

5. Waves Ultimate 17
   - Stage: Advanced Chain Building
   - Outcome: Assemble a vocal chain using selected individual processors.

Also include:

- Complete Vocal Mixing Series
- All five courses
- Five corresponding software packages
- One complete-series price
- No duplicate individual packages when the bundle is selected

==================================================
8. PRODUCT FAMILY 2: AUDIO SOFTWARE
==================================================

Courses:

1. Fender Studio 8
2. Cubase
3. FL Studio
4. Ableton Live
5. Logic Pro
6. Pro Tools

Each course should teach:

- Software setup
- Recording or importing audio
- Editing
- Arrangement
- Organization
- Export

Also include:

- Complete Audio Software Series
- All six courses
- Six corresponding licensed software packages
- Software compatibility information
- Operating-system requirements
- No bundle and individual-package duplication

Do not invent confirmed software editions. Use editable placeholders until product details are supplied.

==================================================
9. PRODUCT FAMILY 3: BEAT MAKING
==================================================

Courses:

1. Beat Making Foundations
2. Drum Programming
3. Melodies & Chords
4. Bass & 808s
5. Sampling & Sound Design
6. Arrangement & Beat Mix

Also include:

- Complete Beat Making Series
- All six courses
- Six corresponding software packages
- Confirmed samples, stems, project files, and resources only
- No invented licensing terms

==================================================
10. ALL COURSES PAGE
==================================================

Create one central course catalog containing every product.

It must include:

- All 17 individual courses
- Complete Vocal Mixing Series
- Complete Audio Software Series
- Complete Beat Making Series

Filters:

- All Courses
- Vocal Mixing
- Audio Software
- Beat Making

Optional level filters:

- Foundation
- Intermediate
- Advanced

Each family should have:

- A clear section heading
- A complete-series bundle card
- Individual course cards
- Link to the dedicated product-family page

Filtering should work without a full-page reload when supported by the existing stack.

==================================================
11. LOGIN PAGE
==================================================

Create a polished split-layout login page.

Left panel:

- Near-black or deep green background
- AK23 Studio Kits / AK23 Academy branding
- Bold headline
- Short description
- Benefits:
  - Downloadable courses
  - Included software licenses
  - Offline learning

Right panel:

- Email
- Password
- Remember me
- Forgot password
- Login button
- Validation errors
- Loading state
- Accessible labels

Do not show student/admin role selection in production.

The backend determines the dashboard using the authenticated user’s assigned role.

After login:

- Student → student dashboard
- Admin → admin dashboard
- Support → support dashboard or permitted admin area
- Content manager → course-management area

==================================================
12. STUDENT DASHBOARD
==================================================

Use the same clean layout system as the admin dashboard.

Layout:

- Fixed near-black sidebar around 232px wide
- Light dashboard background
- 72px top utility header
- White cards
- Border radius around 9–12px
- Thin #DFE3E5 borders
- Restrained shadows
- Responsive slide-out sidebar on mobile

Student sidebar:

Learning:
- My Dashboard
- My Courses
- Downloads
- Software Licenses

Account:
- My Orders
- Profile
- Support
- Logout

Top bar:

- Mobile menu button
- Search
- Notifications
- Student avatar
- Account menu

Student dashboard content:

1. Welcome panel
   - Student’s real name
   - Continue learning message
   - Explore more courses action

2. Summary cards
   - Courses owned
   - Overall progress
   - Available downloads
   - Pending software licenses

3. Continue Learning
   - Current course
   - Current lesson
   - Progress percentage
   - Continue button

4. My Courses
   - Course title
   - Stage
   - Progress bar
   - Status:
     - Not started
     - In progress
     - Complete

5. Software Licenses
   - Software package
   - Delivery status
   - Installer availability
   - Activation status
   - Do not show delivered or activated before confirmation

6. Recent Orders
   - Order number
   - Package
   - Date
   - Total in TSH
   - Payment status

7. Downloads
   - Lesson videos
   - Practice resources
   - File format
   - File size
   - Secure download action

8. Support
   - Create ticket
   - Select category
   - Link related order
   - View responses

Only show information belonging to auth()->id().

==================================================
13. ADMIN DASHBOARD
==================================================

Rebuild the admin dashboard using this layout.

Sidebar width:

- Approximately 232px
- Fixed on desktop
- Slide-out drawer on mobile

Admin sidebar:

Overview:
- Dashboard
- Analytics

Academy:
- Series
- Courses
- Lessons & Files
- Software Licenses

Commerce:
- Orders
- Selcom Payments
- Students
- Entitlements

System:
- REST & RPC API
- Webhooks
- Settings

Bottom user section:

- Admin avatar
- Admin name
- Role
- Logout button

Top header:

- Search field
- New Course button
- Notifications
- Admin avatar
- Mobile menu toggle

Admin dashboard cards:

1. Total revenue
2. Paid orders
3. Active students
4. Licenses pending

Charts and tables:

- Revenue overview
- Orders / students tabs
- Order-status donut chart
- Top-selling courses
- Revenue by series
- Recent Selcom transactions
- Fulfillment health
- Course access readiness
- License delivery percentage
- Selcom reconciliation percentage
- Download availability percentage

Use real database queries.

Do not hardcode production dashboard numbers.

Provide empty states when no data exists.

==================================================
14. ADMIN CRUD FEATURES
==================================================

Series management:

- Create
- Edit
- Archive
- Reorder
- Publish

Course management:

- Create
- Edit
- Archive
- Assign to series
- Stage label
- Learning outcome
- Description
- Course visual
- Lesson count
- Duration
- Accent color
- Status

Lesson management:

- Add lessons
- Reorder lessons
- Preview settings
- Duration
- Video file
- Downloadable files
- Practice resources

Product management:

- Individual course package
- Complete-series bundle
- SKU
- TSH price
- Status
- Included courses
- Included software
- Terms version

Software management:

- Vendor
- Product name
- Edition
- License model
- Compatibility
- Installer URL
- Activation instructions
- Terms confirmation
- License delivery status

==================================================
15. SELCOM PAYMENTS
==================================================

The dashboard must support Selcom payment information.

Display:

- Internal order number
- Selcom order reference
- Selcom transaction reference
- Customer
- Phone number
- Amount
- Currency
- Channel
- Payment state
- Callback verification state
- Created date
- Completed date
- Failure code
- Failure message

Possible payment states:

- Initialized
- Pending
- Successful
- Failed
- Cancelled
- Expired
- Refunded

Security:

- Selcom credentials must only exist in server environment variables
- Never expose credentials to Blade, JavaScript, Vue, React, or API responses
- Verify callback signatures before fulfillment
- Store callback events idempotently
- Do not fulfill courses or licenses before verified payment
- Repeated callbacks must not create duplicate entitlements
- Log verification and processing failures

==================================================
16. REST AND RPC API
==================================================

The Laravel frontend and dashboards should consume a consistent service layer.

Suggested API groups:

Public:
- GET /api/series
- GET /api/courses
- GET /api/products
- GET /api/products/{slug}
- POST /api/checkout/quote
- POST /api/orders

Authenticated student:
- GET /api/me
- GET /api/me/dashboard
- GET /api/me/courses
- PATCH /api/me/lessons/{lesson}/progress
- GET /api/me/downloads
- GET /api/me/licenses
- GET /api/me/orders
- GET /api/me/notifications
- POST /api/me/support/tickets

Administrator:
- GET /api/admin/dashboard
- CRUD /api/admin/series
- CRUD /api/admin/courses
- CRUD /api/admin/lessons
- CRUD /api/admin/products
- CRUD /api/admin/software
- GET /api/admin/orders
- GET /api/admin/payments
- GET /api/admin/students
- GET /api/admin/entitlements
- GET /api/admin/webhooks
- CRUD /api/admin/settings

Use:

- Form Request validation
- API Resources
- Policies
- Service classes
- Database transactions
- Pagination
- Eager loading
- Query scopes
- Consistent error responses

Avoid N+1 queries.

==================================================
17. DATABASE INTEGRATION
==================================================

Use the existing MySQL schema and migrations if present.

Expected tables include:

- profiles
- roles
- user_roles
- auth_accounts
- user_sessions
- password_reset_tokens
- series
- courses
- lessons
- software_packages
- course_software
- products
- product_courses
- product_software
- orders
- order_items
- payment_intents
- payment_events
- entitlements
- enrollments
- lesson_progress
- download_assets
- download_grants
- download_events
- license_deliveries
- notifications
- support_tickets
- support_messages
- api_clients
- webhook_endpoints
- webhook_deliveries
- platform_settings
- audit_log

If Laravel migrations already exist, update them using new additive migrations.

Do not edit old production migrations if they may already have been run.

Create new migrations for schema changes.

==================================================
18. RESPONSIVE REQUIREMENTS
==================================================

Public course grid:

- Large desktop: 3 columns
- Tablet: 2 columns
- Mobile: 1 column

Dashboard:

- Desktop sidebar fixed
- Tablet content reflows
- Mobile sidebar becomes a drawer
- Tables become horizontally scrollable
- Cards stack vertically
- Search adapts to available width
- All buttons remain usable at touch sizes

Test approximately:

- 1440px
- 1280px
- 1024px
- 768px
- 390px

==================================================
19. ACCESSIBILITY
==================================================

Required:

- Semantic headings
- Proper labels
- Keyboard navigation
- Visible focus states
- Accessible modal behavior
- aria-expanded on accordions
- aria-current for active navigation
- Sufficient color contrast
- Descriptive button labels
- Error messages associated with fields
- Do not communicate status using color alone
- Respect reduced-motion preferences

==================================================
20. PERFORMANCE
==================================================

- Paginate large tables
- Eager-load relationships
- Use indexed filters
- Cache public catalog data when appropriate
- Cache dashboard aggregates briefly if needed
- Invalidate cache when catalog or payment data changes
- Lazy-load large media
- Use optimized thumbnails
- Avoid loading full videos on catalog pages
- Use private signed URLs for protected downloads
- Do not return encrypted license keys unnecessarily
- Use queues for:
  - Email
  - License delivery
  - Webhook delivery
  - Selcom reconciliation
  - Large-file processing

==================================================
21. IMPLEMENTATION QUALITY
==================================================

Create reusable components for:

- Buttons
- Status badges
- Course cards
- Bundle cards
- Metric cards
- Dashboard panels
- Data tables
- Progress bars
- Sidebar navigation
- Top navigation
- Empty states
- Loading states
- Validation messages
- Confirmation dialogs
- Pagination
- Search and filters

Do not create one giant Blade, Vue, React, or Livewire file.

Keep controllers thin.

Move business logic into services or actions.

Use policies for authorization.

Use transactions for checkout, payment confirmation, and entitlement creation.

==================================================
22. TESTING
==================================================

Add tests for:

Authentication:
- Student login
- Admin login
- Failed login
- Role redirects
- Student denied admin access
- Logout

Catalog:
- Public courses load
- Product filters work
- Bundles contain the correct courses
- Draft products cannot be purchased

Checkout:
- Single-course purchase
- Complete-series purchase
- Bundle overlap is rejected
- Server calculates totals
- Duplicate orders are prevented

Payments:
- Verified Selcom payment creates entitlements
- Invalid signature does not fulfill an order
- Duplicate callback does not duplicate entitlements
- Failed payment does not grant downloads

Student:
- User sees only their courses
- User sees only their orders
- Lesson progress updates
- Downloads require entitlement
- License statuses are accurate

Admin:
- Admin dashboard loads
- Student cannot access admin routes
- Course CRUD works
- Order and payment filters work

Run the project’s documented tests, formatter, linter, and build commands.

==================================================
23. FINAL DELIVERY
==================================================

When finished, report:

1. Files changed
2. Routes added
3. Components created
4. Controllers and services created
5. Migrations added
6. Policies and middleware added
7. Tests added
8. Verification commands run
9. Any placeholders still requiring real data
10. Any Selcom configuration still required

Do not claim that payments, licenses, downloads, or authentication are working unless they have been tested successfully.

Do not invent:

- Software editions
- Software license terms
- Course prices
- Tax rates
- Refund rules
- Selcom credentials
- Student reviews
- Discounts
- License keys
- Delivery statuses

Use explicit placeholders where information is unconfirmed.