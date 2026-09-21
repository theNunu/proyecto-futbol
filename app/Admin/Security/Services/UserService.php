<?php

namespace App\Admin\Security\Services;

// use App\Admin\Repository\UserRepository;

use App\Admin\Security\Repository\UserRepository;
use App\Models\Person;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        // dd('sasqas');
        $this->userRepository = $userRepository;
    }

    // 📝 REGISTER
    public function register(array $data)
    {
        // dd($data);
        $this->emailExsits($data['email']);

        $username = $this->generateUserName($data["first_name"], $data["last_name"]);

        if ($data["identification_type"] !== "DEPORTE_ERP" && $data["identification_type"] !== "CONTABILIDAD_ERP") {
            throw ValidationException::withMessages([
                'identification_type' => ['Tipo de identificación es inválido.']
            ]);
        }
        //validar email unico

        $personCreated = Person::create([  //1. crear PERSONA
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'identification_type' => $data['identification_type'],
            'identification_number' => $data['identification_number'],
            'image_url' => $data['image_url'] ?? null,
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'birth_date' => $data['birth_date']
        ]);

        // dd('CREDENCIALES: ', $data, "username: ", $username, $data['email'], $personCreated, $personCreated->person_id);
        $password = $this->generatePassword();
        $user = User::create([  //2. crear Usuario para la pagina
            'name' => $username,
            'email' => $data['email'],
            'password' => Hash::make($password), //hashear contraseña
            'person_id' => $personCreated->person_id
        ]);


        //asignar roles a usuarios

        if ($data['roles']) {
            foreach ($data['roles'] as $role) {
                // 2. Asignar múltiples roles a la vez
                $user->roles()->attach([$role]);
            }
        }

        // return $user;
        return [
            "usuario_creado" => $user,
            "password" => $password
        ];
    }


    private function generateUserName($firstName, $lastName)
    {
        $firstCaracter = Str::of($firstName)->substr(0, 1); // Resultado: "L" obtener primera letra de fist_name
        $username = $firstCaracter . $lastName;

        $count = User::where('name', 'ILIKE', "%{$username}%")->count(); // validatr si el usrname para user existe
        $username = $count > 0 ? "{$username}" . ($count + 1) : $username;
        return $username;

    }

    private function generatePassword()
    {
        return random_int(1000, 9999);  // Exactamente 4 dígitos (entre 1000 y 9999)
        // return Hash::make($password);
    }

    private function emailExsits($email)
    {

        $exists = User::where('email', $email)->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'email_already_exists' => ['El correo ya esta en uso. ']
            ]);

        }
        return $exists;
        // $password = random_int(1000, 9999);  // Exactamente 4 dígitos (entre 1000 y 9999)
        // return Hash::make($password);
    }
}