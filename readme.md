
# Strategio SaaS
Most powerful tool for creating webs, apps & APIs.

Demo: https://saas.strategio.dev (u: admin@test.cz p: Test1234)

<img src="https://jzapletal.s3.eu-west-1.amazonaws.com/strategio-saas-edit-data.png" width="100%" alt="Strategio SaaS">

## Installation guide
1. Create project by `curl -sL bit.ly/3AnA49z | bash /dev/stdin create <project-folder>`
2. Move to your project folder & finish installation steps by [readme.md](https://github.com/strategio-digital/saas/blob/master/sandbox/readme.md)

## Core features
- 🟢 Web-ready dev-stack (simple router & Latte templates)
- 🟢 API-ready dev-stack (simple router & UI route permissions editor)
- 🟢 Fully configurable & extendable Vue 3 Admin panel.
- 🟠 Admin panel with UI datagrid editor based on Doctrine entities.
- 🟢 Vite assets bundler for fast compiling scss, ts, vue, etc.
- 🟢 One click deployment with Dockerfile and easypanel.io.
- 🟢 Stateless and scalable architecture for PHP applications.
- 🟢 Optimized Docker image (Nginx & PHP-FPM) - about 20Mb costs

## Backend features
- 🟢 JWT Auth with route resources protection.
- 🟢 Requests validation by Nette\Schema.
- 🟢 Symfony events & event subscribers for a lot of stuff.
- 🟢 Fully integrated Doctrine ORM.
- 🟢 Symfony Http\Kernel for handling requests.
- 🟢 File storage with AWS S3 adapter.
- 🟢 Tracy\Debugger with AWS S3 logger adapter.
- 🟢 Custom extensions with Nette\DI\Extensions.
- 🟢 Custom Symfony console commands.
- 🟢 PHPStan static analysis on level 8.

## Priority
- 🟡 Brainstorm entity mapping via orisai/object-mapper
- 🟡 Collections edit / update page with custom vue-components
- 🟡 CRUD request-validation by entity props
- 🟠 Collection CRUD form with most useful field types (inspired by Nova & Pocketbase)
- 🟠 Enhanced CRUD with inner/outer joins 1:1, 1:N, M:N
- 🟠 MultiFile uploader
- 🟠 Admin datagrid + CRUD
- 🟠 Make some docs (inspired by [docusaurus.io](https://docusaurus.io/))
- 🟠 Custom page components (inspired by Strapi.io)

## Backlog
- 🟠 Scheduled database backups to S3 & log dashboard
- 🟠 AI text helper (Chat GPT + PHP Storm style)
- 🟠 JSON translations (i18n, untranslated text finder, AI auto translate)
- 🟠 **Collection Editor** (Doctrine entity builder & safe migrations)
- 🟠 App settings (edit envFile - dev only)
- 🟠 Storage settings (edit envFile - dev only)
- 🟠 E-mail settings (edit envFile - dev only)
- 🟠 Console cron jobs & progress dashboard
- 🟠 Console redis jobs & progress dashboard
- 🟠 Access log & error log dashboard with Tracy/BlueScreens
- 🟠 Add cart extension (React with GoPay)
- 🟠 Add multi-tenant extensions
- 🟠 Button for fake-data bulk insert in collections
- 🟠 Make intro videos (for developers & for administrators)

### Make some automatic tests
- Phpstan
- Nette tester / PHP Unit
- Vulnerability audits
- Cors tests from another domain
- Doctrine schema-validation
- API endpoints tests
- Sandbox project deploy (easypanel project with webhook)

## Changelog

### 07/2023
- 🟢 App.php refactoring
- 🟢 Upgrade to symfony router 6.3
- 🟢 Add Symfony\Kernel and controller argument resolver with autowiring
- 🟢 Bootstrap.php refactoring (make it extendable in neon)
- 🟢 Add kernel events (CSP, CORS)
- 🟢 Split User entity into User & Admin entity
- 🟢 Admin & User login mechanism refactoring
- 🟢 Make User entity commutable and test it in sandbox project
- 🟢 Add Collection CRUD events & Application events
- 🟢 Add JWTAuth mechanism for Routes, Collections, CollectionNav
- 🟢 Split Login form into Admin & User form
- 🟢 Add Alert system and show alerts on response status 40X
- 🟢 Add Nginx request rate limiter & Symfony IP address proxy resolver
- 🟢 Add navbar resources, Vue composable and hide non-admin stuff
- 🟢 Resource loader for vue router & update button in admin panel.
- 🟢 Role access table (Routes, Collections, CollectionsNav, Views)
- 🟢 Role add modal, role remove modal, cascade delete in SQLite / Postgres.
- 🟢 Print composer.lock & yarn.lock version in admin panel
- 🟢 Doctrine SQL profiler: TracyBar, JsonResponse debugger (Queries count, SQL log, execution times)

### 06/2023
- 🟢 API end-point for CRUD actions trough Doctrine Entities
- 🟢 Extendable (collections) datagrid with global configs

## Tutorials

### 1. Video tutorials coming soon...

### 2. How to debug API with [Postman](https://documenter.getpostman.com/view/14885541/2s8YsqUZuv).

If you want to use Postman to debug API, just add this script into `Postman -> Collection -> Tests` section and you will be able to use Tracy\Debuuger in Postman.
```JS
pm.test("set html", function() {
    var regex = /\"(.*)(\_tracy\_bar)/gm
    var protocol = pm.request.url.protocol
    var host = pm.request.url.host
    var port = pm.request.url.port
    var hostPort = port ? `${host}:${port}` : host

    var html = pm.response.text()
    var fixedHtml = html.replaceAll(regex, `${protocol}://${hostPort}$1$2`)

    pm.visualizer.set(fixedHtml)
});
```
