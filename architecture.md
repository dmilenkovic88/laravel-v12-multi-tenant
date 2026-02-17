# Multi-Tenant Arhitektura (Laravel 12 + Livewire 4)

## Pregled
Aplikacija koristi **Database-per-Tenant** pristup. 
- **Landlord DB**: Korisnici, Tenanti, Moduli i Pivot tabele.
- **Tenant DB**: Specifični podaci modula (Accounts, Tasks, itd.).

## Core Principi
1. **Tenant Isolation**: Nijedan upit ne sme procureti između tenanata.
2. **Context Awareness**: `TenantContext` je izvor istine za trenutno aktivnog tenanta.
3. **Module Control**: Moduli se proveravaju preko `@module` direktive (O(1) kompleksnost putem keširanog mapiranja).
4. **Stateless UI**: URL ne sadrži ID tenanta; identifikacija ide preko sesije i Context-a.

## Tehnološki Stack
- Laravel 12 (PHP 8.4+)
- Livewire 4 (Volt ili Class-based)
- Flux UI (Utility-first components)
- Redis (Caching tenant/module mappings)
