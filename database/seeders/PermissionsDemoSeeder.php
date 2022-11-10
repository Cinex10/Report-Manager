<?php

namespace Database\Seeders;

use App\Models\Categorie;
use App\Models\Declaration;
use App\Models\Service;
use App\Models\Solution;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Support\Facades\DB;


class PermissionsDemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();



        // //Permissions
        Permission::create(['name' => 'GERER ROLES']);
        Permission::create(['name' => 'GERER USERS']);
        Permission::create(['name' => 'CONSULTE USERS']);
        Permission::create(['name' => 'GERER COMPTE']);
        Permission::create(['name' => 'GERER CATEGORIE']);
        Permission::create(['name' => 'GERER SERVICE']);
        Permission::create(['name' => 'GERER ANNONCE']);
        Permission::create(['name' => 'CONSULTE ANNONCE']);
        Permission::create(['name' => 'GERER DECLARATION']);
        Permission::create(['name' => 'CONSULTE DECLARATION']);
        Permission::create(['name' => 'GERER DECLARATION DE SERVICE']);
        Permission::create(['name' => 'ADD DECLARATION']);
        Permission::create(['name' => 'DEL COMPTE']);
        Permission::create(['name' => 'CONSULTE PROFILE']);
        Permission::create(['name' => 'MAJ PROFILE']);
        Permission::create(['name' => 'COMPLETE DECLARATION']);
        Permission::create(['name' => 'ADD RAPPORT']);
        Permission::create(['name' => 'ADD CATEGORIE']);
        Permission::create(['name' => 'COMPLETE RAPPORT']);
        Permission::create(['name' => 'ADD ANNONCE']);


        // //Roles
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo('GERER ROLES');
        $admin->givePermissionTo('GERER USERS');
        $admin->givePermissionTo('GERER COMPTE');




        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo('ADD DECLARATION');
        $user->givePermissionTo('CONSULTE PROFILE');
        $user->givePermissionTo('CONSULTE DECLARATION');
        $user->givePermissionTo('MAJ PROFILE');
        $user->givePermissionTo('COMPLETE DECLARATION');
        $user->givePermissionTo('CONSULTE ANNONCE');



        $user = Role::create(['name' => 'responsable']);
        $user->givePermissionTo('GERER CATEGORIE');
        $user->givePermissionTo('CONSULTE DECLARATION');
        $user->givePermissionTo('GERER DECLARATION');
        $user->givePermissionTo('CONSULTE USERS');
        $user->givePermissionTo('GERER ANNONCE');
        $user->givePermissionTo('ADD CATEGORIE');




        $chef_service = Role::create(['name' => 'chef service']);
        $chef_service->givePermissionTo('GERER DECLARATION DE SERVICE');
        $chef_service->givePermissionTo('ADD RAPPORT');
        $chef_service->givePermissionTo('COMPLETE RAPPORT');
        $chef_service->givePermissionTo('GERER SERVICE');
        $chef_service->givePermissionTo('ADD ANNONCE');


        //----------------------------------- USERS ---------------------------------------------------//


        // Create admin user
        $admin = User::create(
            [
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'admin@gmail.dz',
                'adress' => 'Saida, alger',
                'tel' => '0523456789',
                'isActive' => true,
                'password' => bcrypt('1234'),

            ]
        );



        //Create simple user (1)
        $user1 = User::create(
            [
                'first_name' => 'Rayan',
                'last_name' => 'Mohammed',
                'email' => 'user1@gmail.dz',
                'adress' => 'Tlemcen, alger',
                'tel' => '0523456789',
                'isActive' => true,
                'password' => bcrypt('1234'),
            ]
        );


        //Create simple user (2)
        $user2 = User::create(
            [
                'first_name' => 'Abdulah',
                'last_name' => 'Walid',
                'email' => 'user2@gmail.dz',
                'adress' => 'Tlemcen, alger',
                'tel' => '0523456789',
                'isActive' => true,
                'password' => bcrypt('1234'),
            ]
        );

        //Create responsable user
        $responsable = User::create(
            [
                'first_name' => 'DRISS',
                'last_name' => 'Yassine',
                'email' => 'resp@gmail.dz',
                'adress' => 'Bechar, alger',
                'tel' => '0523456789',
                'isActive' => true,
                'password' => bcrypt('1234'),
            ]
        );

        //Create chef service user (1) 
        $chef_service1 = User::create(
            [
                'first_name' => 'Chef',
                'last_name' => 'one',
                'email' => 'chef1@gmail.dz',
                'adress' => 'SBA, alger',
                'tel' => '0523456789',
                'isActive' => true,
                'password' => bcrypt('1234'),
            ]
        );
        //Create chef service user (2) 
        $chef_service2 = User::create(
            [
                'first_name' => 'Chef',
                'last_name' => 'Two',
                'email' => 'chef2@gmail.dz',
                'adress' => 'Setif, alger',
                'tel' => '0677538293',
                'isActive' => true,
                'password' => bcrypt('1234'),
            ]
        );
        //----------------------------------- ASSIGN ROLES---------------------------------------------------//        
        $admin->assignRole('admin');
        $user1->assignRole('user');
        $user2->assignRole('user');
        $responsable->assignRole('responsable');
        $chef_service1->assignRole('chef service');
        $chef_service2->assignRole('chef service');

        //----------------------------------- SERVICES ---------------------------------------------------//        

        //Create service 1 and attache chef one
        $service1 = Service::create([
            'name' => 'Travaux Publique',
            'phone' => '049543881',
            'email' => 'ta@gmail.com',
            'description' => 'le service chagée de travaux publique',
            'idChefService' => $chef_service1->id,
        ]);

        //Create service 2 and attache chef two
        $service2 = Service::create([
            'name' => 'Electricite',
            'phone' => '049609183',
            'email' => 'elec@gmail.com',
            'description' => "le service chagée des problemes d'electricite",
            'idChefService' => $chef_service2->id,
        ]);


        //----------------------------------- CATEGORIES ---------------------------------------------------//        

        //Create categorie 1 and attache service 1       
        $cat1 =  Categorie::create(
            [
                'idService' => $service1->id,
                'name' => 'Urbanism',
                'description' => 'description de categorie urbanism',


            ]
        );


        //Create categorie 2 and attache service 2
        $cat2 = Categorie::create([
            'idService' => $service2->id,
            'name' => 'Eclairage',
            'description' => 'description de categorie eclairage',
        ]);

        //----------------------------------- DECLARATIONS ---------------------------------------------------//        

        //Create declaration (1) (new)
        // $dec1 = Declaration::create([
        //     'idUser' => $user1->id,
        //     'titre' => 'Probleme A1',
        //     'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Exercitationem veritatis, doloremque aspernatur, est eligendi iste, nulla distinctio nihil ducimus commodi nisi? Autem incidunt vitae non, assumenda soluta aut impedit atque.',
        //     'lieu' => 'Tlemcen',
        //     'idCategorie' => 1,
        //     'idDeclarationParent' => null,
        // ]);


        // //Create declaration (2) (new)
        // $dec2 = Declaration::create([
        //     'idUser' => $user1->id,
        //     'titre' => 'Report B2',
        //     'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Exercitationem veritatis, doloremque aspernatur, est eligendi iste, nulla distinctio nihil ducimus commodi nisi? Autem incidunt vitae non, assumenda soluta aut impedit atque.',
        //     'lieu' => 'SBA',
        //     'idCategorie' => 1,
        //     'idDeclarationParent' => null,
        // ]);


        // //Create declaration (3) (valid)
        // $dec3 = Declaration::create([
        //     'idUser' => $user1->id,
        //     'titre' => 'Report probleme C3',
        //     'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Exercitationem veritatis, doloremque aspernatur, est eligendi iste, nulla distinctio nihil ducimus commodi nisi? Autem incidunt vitae non, assumenda soluta aut impedit atque.',
        //     'lieu' => 'Alger',
        //     'idCategorie' => 1,
        //     'state' => 'valid',
        //     'idDeclarationParent' => null,
        // ]);


        // //Create declaration (4) (valid)
        // $dec4 = Declaration::create([
        //     'idUser' => $user1->id,
        //     'titre' => 'Probleme D4',
        //     'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Exercitationem veritatis, doloremque aspernatur, est eligendi iste, nulla distinctio nihil ducimus commodi nisi? Autem incidunt vitae non, assumenda soluta aut impedit atque.',
        //     'lieu' => 'Bechar',
        //     'idCategorie' => 1,
        //     'state' => 'valid',
        //     'idDeclarationParent' => null,
        // ]);

        // //Create declaration (5) (rejected)
        // $dec5 = Declaration::create([
        //     'idUser' => $user1->id,
        //     'titre' => 'Report ZFOU',
        //     'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Exercitationem veritatis, doloremque aspernatur, est eligendi iste, nulla distinctio nihil ducimus commodi nisi? Autem incidunt vitae non, assumenda soluta aut impedit atque.',
        //     'lieu' => 'Tizi ouzou',
        //     'idCategorie' => 1,
        //     'state' => 'rejected',
        //     'idDeclarationParent' => null,
        // ]);


        // //Create declaration (6) (rejected)
        // $dec6 = Declaration::create([
        //     'idUser' => $user1->id,
        //     'titre' => 'Probleme JHK',
        //     'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Exercitationem veritatis, doloremque aspernatur, est eligendi iste, nulla distinctio nihil ducimus commodi nisi? Autem incidunt vitae non, assumenda soluta aut impedit atque.',
        //     'lieu' => 'Tizi ouzou',
        //     'idCategorie' => 1,
        //     'state' => 'rejected',
        //     'idDeclarationParent' => null,
        // ]);


        // //Create declaration (7) (solved)
        // $dec7 = Declaration::create([
        //     'idUser' => $user1->id,
        //     'titre' => 'Report ZFOU',
        //     'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Exercitationem veritatis, doloremque aspernatur, est eligendi iste, nulla distinctio nihil ducimus commodi nisi? Autem incidunt vitae non, assumenda soluta aut impedit atque.',
        //     'lieu' => 'Tizi ouzou',
        //     'idCategorie' => 1,
        //     'state' => 'solved',
        //     'idDeclarationParent' => null,
        // ]);


        // //Create declaration (8) (solved)
        // $dec8 = Declaration::create([
        //     'idUser' => $user1->id,
        //     'titre' => 'Probleme JHK',
        //     'description' => 'Lorem, ipsum dolor sit amet consectetur adipisicing elit. Exercitationem veritatis, doloremque aspernatur, est eligendi iste, nulla distinctio nihil ducimus commodi nisi? Autem incidunt vitae non, assumenda soluta aut impedit atque.',
        //     'lieu' => 'Tizi ouzou',
        //     'idCategorie' => 1,
        //     'state' => 'solved',
        //     'idDeclarationParent' => null,
        // ]);


        //----------------------------------- SOLUTIONS ---------------------------------------------------//

        // //Create solution (1) (in review)
        // $sol1 = Solution::create([
        //     'idDeclaration' => $dec3->id,

        //     'idChefService' => $chef_service1->id,
        //     'State' => 'in review',
        //     'Titre' => 'Solution AYV',
        //     'Description' => 'description de la solution',
        // ]);


        // //Create solution (2) (incomplete)
        // $sol2 = Solution::create([
        //     'idDeclaration' => $dec4->id,

        //     'idChefService' => $chef_service1->id,
        //     'State' => 'incomplete',
        //     'Titre' => 'Solution BIG BOSS',
        //     'Description' => 'description de la solution',
        // ]);

        // //Create solution (3) (solved)
        // $sol3 = Solution::create([
        //     'idDeclaration' => $dec7->id,

        //     'idChefService' => $chef_service1->id,
        //     'State' => 'solved',
        //     'Titre' => 'Solution Final',
        //     'Description' => 'description de la solution',
        // ]);
        // //Create solution (4) (solved)
        // $sol4 = Solution::create([
        //     'idDeclaration' => $dec8->id,

        //     'idChefService' => $chef_service1->id,
        //     'State' => 'solved',
        //     'Titre' => 'Solution Terminal',
        //     'Description' => 'description de la solution',
        // ]);
    }
}
