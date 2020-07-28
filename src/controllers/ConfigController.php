<?php
namespace src\controllers;
use \core\Controller;
use DateTime;
use \src\handlers\UserHandler;
class ConfigController extends Controller {
     
    private $loggedUser;
    public function __construct() {
        $this->loggedUser = UserHandler::checkLogin();
        if($this->loggedUser === false){
            $this->redirect('/signin');
        }
    }
    public function index(){
        //Mensagem exibida na tela, caso exista.
        $flash = "";        
        if(!empty($_SESSION['flash'])){
            $flash = $_SESSION['flash'];
            $_SESSION['flash'] = '';
        }
        
        //Montnado a tela de configuração do usuário
        $this->render('config',[
            'loggedUser' => $this->loggedUser,
            'flash' => $flash                
        ]);
    }
    public function configAction(){
        //Recebe os dados do formulário
        $email = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST,'password');
        $name = filter_input(INPUT_POST,'name',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $birthDate = filter_input(INPUT_POST,'birthDate',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $city = filter_input(INPUT_POST,'city',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $work = filter_input(INPUT_POST,'work',FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $avatar = filter_input(INPUT_POST,'avatarFile');
        $cover = filter_input(INPUT_POST,'coverFile');
        $newPassword = filter_input(INPUT_POST,'newPassword');
        if(!$email && !$password && !$name && !$birthDate && !$city && !$work && !$avatar && !$cover ){
            $this->redirect("/config");
        }
        //Pega os dados do usuário logado no BD
        $user = UserHandler::getUser($this->loggedUser->id, false);
        
        //Verificação do email
        if(!$email){
            $email = $user->email;
        } else if((UserHandler::emailExists($email)) && ($email != $user->email)) {
            $_SESSION['flash'] = "Email já utilizado por outro usuário";
            $this->redirect("/config");
        }
        
        //Verificação da senha
        if( $password != $newPassword) {
            $_SESSION['flash'] = "Campos 'Nova Senha' e 'Confirmar Nova Senha' diferentes! ";
            $this->redirect("/config");
        } 
        //Verificão do nome
        if(empty($name)){
            $name = $user->name;
        }
        //Verificação da data
        if(empty($birthDate)){
            $birthDate = $user->birthdate;
        } else { 
           $birthDate = explode("-", $birthDate);
           // mes/dia/ano
           if(!checkdate($birthDate[1], $birthDate[2],$birthDate[0])){
               $_SESSION['flash'] = "Data de nascimento inválida!";
               $this->redirect("/config");
           }
           //ano, mes, dia
           $birthDate = $birthDate[0].'-'.$birthDate[1].'-'.$birthDate[2]; 
           //verifica se é maior de 16 anos
           $dateFrom = new \DateTime($birthDate);
           $dateTo = new \DateTime('today');
           $ageYears = ($dateFrom->diff($dateTo));
           if( ($ageYears->y < 16) || ($ageYears->invert == 1)){
                $_SESSION['flash'] = "Menor de 16 anos!";
                $this->redirect("/config"); 
           }
        }
        //Verificão da cidade
        if(empty($city)){
            $city = $user->city;
        }
        //Verificão do trabalho
        if(empty($work)){
            $work = $user->work;
        }
        //Verificão do avatar
        if(empty($avatar)){
            $avatar = $user->avatar;
        }
        //Verificão do trabalho
        if(empty($cover)){
            $cover = $user->cover;
        }
    UserHandler::updateUser($this->loggedUser->id, $email, $password, $name, $birthDate, $city, $work, $avatar, $cover);   
    $this->redirect("/config");   
    }
    
}//fim da classe 