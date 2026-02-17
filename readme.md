## Dodato username i active za korisnika

app/Actions/Fortify/CreateNewUser.php //dodato username polje
app/Concerns/ProfileValidationRules.php  //dodato username polje
app/Livewire/Settings/Profile.hpp //dodato username polje
app/Models/User.php // dodata polja i getFormattedIdAttribute
database/migrations/2026_01_01_000001_add_username_to_user.php
resources/views/components/desktop-user-menu.blade.php //zamenjen prikaz fullname u username
resources/views/layouts/app/sidebar.blade.php //username za prikaz aktivnog korisnika
resources/views/livewire/auth/register.blade.php //dodato username polje
resources/views/livewire/settings/profile.blade.php //dodato username polje


echo "# laravel-v12-starter-kit" >> README.md
git init
git add .
git commit -m "commit name"
git branch -M main
git remote add origin https://github.com/dmilenkovic88/laravel-v12-starter-kit.git
git push -u origin main
