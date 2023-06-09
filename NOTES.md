# Challenge - Updates and Notes

## Assumptions

An assumption has been made that Doctors can only me merged when they are a duplicate from the same clinic. So a Doctor that works are multiple clinics will be listed at least once for each. Given more time I would probably change this using a linking table but that also requires more UI changes and also effects the Tests that need to be linked to the doctors AND clinic seperately which in turn requires checks.

## Libraries

I have used [Laravel Livewire Tables](https://github.com/rappasoft/laravel-livewire-tables) as this provides advanced table features and styling out of the box. I then used the Bulk Actions feature for merges. This in turn meant adding Livewire and AlpineJS which have both been added via composer and yarn respectively.

## Tasks

### Normalize the `doctors` table by splitting it into two tables:

This has been done via a migration and seeder that populates the new clinics table fron the doctors table. 

**Please note that the DB in repo is post migrations**

**It is impportant to NOT run a standard migration** else Doctor columns will be removed before data is moged to the clinic table therefore run... 
`php artisan migrate --path=database/migrations/2023_03_17_051999_create_clinics_table.php --seed --seeder=ClinicsTableSeeder`

Then a second migration that drops the old columns from the doctors table, run...
`php artisan migrate --path=database/migrations/2023_03_19_052009_drop_fields_from_doctors_table.php`

### Maintain the relationship between the new tables and tests, ensuring the tests index displays the same information after your changes.

1. Clinic model created and maintains an hasMany relationship to Doctor model.
2. Doctor model modified to has a belongsTo relationship to Clinic model.
3. Doctor model to run with the clinic relationshiop as standard.
4. Tables views utilise relationships
5. Doctor/Show view utilises relationships

### Create a user interface to perform the following actions:

1. From the table view of Clinics or Doctors you can filter and order the rows.
2. Making sure that the row you want to merge into (destination) is first in the list. select it and teh rows you wish to merge.
3. Clink Bulk Actions and select Merge.
4. Merges
    1. For clinic Doctors from the selected Clinics are all re-assigned to the destition clinic. The merged clinics are then deleted.
    2. For Doctors there is a check that each merge doctor is of the same clinic as the destination doctor. If the row passes that check the tests from the merging doctors are reassigned to the destination Doctor. The merges Doctors are then deleted.

## Additional Notes

### Navigation

I added navigation just to make it easier getting around.

### Tables

For simplicity I have migrated all table views to use Livewire Tables

### Edit/Create Doctors

I have changed the fields to utilise a select list built from the Clinics (excluding blanks)


## Update 28/03/2023

Had an hour up sleave to makes changes after clarification of the desired results. Was not completed but this is what was achieved.
 
- New migration to create a pivot table for doctors and clinics.
- New seeder to populate the new table.
- Modified the Doctor model for the new belongsToMany relationship.
- Modified the Clinic model for the new belongsToMany relationship.
- Removed the clinc name and address columns from the DoctorsTable (Livewire). 
- Added with a Clinic count column to DoctorsTable
- Added a ability to list multiple clinics in the doctor.show blade 


## Todo
- Modify the migrations to allow for multiple clinics
- Modify the Tests (model and views) to related to a specific doctor/clinic record in the pivit table