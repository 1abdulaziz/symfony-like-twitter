 
1. debugging the code
- lando composer require --dev symfony/profiler-pack

2. create migration
- lando console make:migration
- lando console doctrine:migrations:migrate

3. load fixtures
- lando console doctrine:fixtures:load
- lando console doctrine:fixtures:load --group=dev
- lando console make:fixtures 

4. create entity
- lando console make:entity
- lando console make:entity --regenerate App
- lando console make:entity --regenerate App --overwrite
- lando console make:entity --regenerate App --no-backup
- lando console make:entity --regenerate App --no-backup --overwrite
- lando console make:entity --regenerate App --no-backup --overwrite --api-resource
- lando console make:entity --regenerate App --no-backup --overwrite --api-resource --force

5. create controller
6. create form

7. composer
- lando composer require sensio/framework-extra-bundle 