<?php
include_once 'class/User.class.php';
include_once 'dao/UserDao.class.php';
include_once 'class/class.phpmailer.php';
include_once 'class/class.smtp.php';

//DATABASE FUNCTIONS BEGIN

function databaseConnect($dbHost, $dbUser, $dbPass, $dbName) {
    //Attempt to connect to the MySQL server
    $dbConn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    if ($dbConn->connect_error):
        die("<h1>Erro ao tentar se conectar ao banco de dados!<h1>");
    endif;

    return $dbConn;
}

function databaseDisconnect($dbConn) {
    $dbConn->close();
}

//DATABASE FUNCTIONS END
//AUTHENTICATION FUNCTIONS BEGIN

function logIn($user) {
    session_start();
    $_SESSION["logged"] = true;
    $_SESSION["userId"] = $user->getId();
    $_SESSION["userEmail"] = $user->getEmail();
    $_SESSION["userPass"] = $user->getPass();
    $_SESSION["userType"] = $user->getType();
    $_SESSION["userActive"] = $user->getActive();
    //DEFINE TIME SESSION LIMIT
    $_SESSION["startTime"] = time();
    header('Location: controlpanel.php');
}

function sessionCheck() {
    session_start();

    //CHECK IF THE USER IS LOGGED
    if ($_SESSION['logged']):
        $minutesInactive = 5;
        $maxTimeInactive = $minutesInactive * 60;
        $timeInactive = time() - $_SESSION["startTime"];
        //CHECK TIME TO EXPIRE SESSION. MAX INACTIVE TIME IS X MINUTES
        if (!($timeInactive >= $maxTimeInactive)):
            $_SESSION["startTime"] = time();
            return 'userIsLogged';
        else:
            //SESSION HAS EXPIRED
            updateLog('timeOut', UserDao::getUserById($_SESSION["userId"]), null, true);
            $_SESSION["timeOut"] = true;
            return 'sessionTimeOut';
        endif;   
    else:
        return 'userIsNotLogged';
    endif;
}

function isAdm() {
    if ($_SESSION["userType"] == 0):
        return true;
    else:
        return false;
    endif;
}

//AUTHENTICATION FUNCTIONS END
//SEND E-MAIL FUNCTIONS BEGIN

function emailForgotPass($email) {
    session_start();
    //INITIATE HTML MESSAGE BEGIN
    $message = "<!DOCTYPE html>
<html lang='pt-BR' style='margin: 0;padding: 0;width: 700px;font-family: 'Lato', sans-serif;background-color: #D3D3D3;'>
    <head style='margin: 0;padding: 0;'>
        <meta charset='UTF-8' style='margin: 0;padding: 0;'>
        <title style='margin: 0;padding: 0;'>E-mail</title>
    </head>
    <body style='margin: 0;padding: 0;width: 700px;font-family: 'Lato', sans-serif;background-color: #D3D3D3;'>
        <header style='margin: 0;padding: 0;background-color: #C0C0C0;height: 150px;box-sizing: border-box;'>
            <div class='container' style='margin: 0;padding: 0 5%;'>
                <img src='http://devloopers.com.br/img/logo.png' alt='DevLoopers' style='height: 130px;margin: 10px 0;padding: 0;position: relative;float: left;'>
                <h1 class='fontzero' style='margin: 0;padding: 0;font-size: 0em;'>E-mail de recuperação de senha</h1>
                <h2 style='margin: 0;padding: 0;width: 470px;margin-top: 45px;position: relative;float: left;text-decoration: none;text-align: center;font-size: 2.7em;color: #E1140B;'>Painel de controle</h2>
            </div>
        </header>

        <section style='margin: 0;padding: 0;'>
            <div class='container' style='margin: 0;padding: 0 5%;'>
                <div class='messageHeader' style='margin: 30px 0;padding: 0;'>
                    <p style='margin: 0;padding: 0;text-decoration: none;text-align: left;font-size: 2.3em;font-weight: bold;color: #5093D7;'>Prezado usuário, </p>
                </div>
                <div class='messageContent' style='margin: 30px 0;padding: 0;'>
                    <p style='margin: 10px 0;padding: 0;text-decoration: none;text-align: left;font-size: 1.3em;color: #5093D7;'>Você está recebendo este e-mail porque foi efetuada uma solicitação de troca de senha deste endereço eletrônico.</p>
                    <p style='margin: 10px 0;padding: 0;text-decoration: none;text-align: left;font-size: 1.3em;color: #5093D7;'>Caso tenha solicitado a troca de senha, <a href='http://controlpanel.devloopers.com.br/controlpanel_functions/changeForgottenPass.php?email={$email}' style='margin: 0;padding: 0;'>Clique aqui</a> para efetuar a troca. Caso não tenha solicitado, favor desconsiderar este e-mail.</p>
                    <p style='margin: 10px 0;padding: 0;text-decoration: none;text-align: left;font-size: 1.3em;color: #5093D7;'>Caso esteja recebendo este e-mail de forma ininterrupta, favor contatar o administrador do sistema.</p>
                </div>
                <div class='messageFooter' style='margin: 30px 0;padding: 0;'>
                    <p style='margin: 10px 0;padding: 0;text-decoration: none;text-align: left;font-size: 2.3em;color: #5093D7;'>Atenciosamente,</p>
                </div>
            </div>
        </section>

        <footer style='margin: 0;padding: 0;background-color: #C0C0C0;height: 80px;box-sizing: border-box;'>
            <div class='container' style='margin: 0;padding: 0 5%;'>
                <a href='http://www.devloopers.com.br' target='_blank' style='margin: 0;padding: 0;'><img src='http://devloopers.com.br/img/logo.png' alt='DevLoopers' style='height: 60px;margin: 10px 0;padding: 0;position: relative;float: left;'></a>
                <p style='margin: 0;padding: 0;width: 380px;margin-top: 15px;margin-left: 100px;position: relative;float: left;text-decoration: none;text-align: left;font-size: 1.3em;color: #5093D7;'><b style='margin: 0;padding: 0;width: 400px;font-weight: bold;color: #E1140B;'>Equipe DevLoopers</b> &copy 2016. Todos os direitos reservados.</p>
            </div>
        </footer>
    </body>
</html>";
    //INITIATE HTML MESSAGE END
    
    if (sendEmail($email, $message)):
        session_start();
        $_SESSION['logged'] = true;
        header('Location: result.php?resultMessage=sendEmail-success');
    else:
        session_start();
        $_SESSION['logged'] = true;
        header('Location: result.php?resultMessage=sendEmail-failure');
    endif;
}

function sendEmail($email, $message) {
    // Inicia a classe PHPMailer
    $mail = new PHPMailer();
    
    // Define os dados do servidor e tipo de conexão
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->IsSMTP(); // Define que a mensagem será SMTP
    $mail->Mailer = 'smtp';
    $mail->Host = "srv168.prodns.com.br"; // Endereço do servidor SMTP
    //$mail->Host = "devloopers.com.br"; // Endereço do servidor SMTP
    //$mail->Host = 'localhost';  // Endereço do servidor SMTP
    $mail->Port = 465;
    //$mail->Port = 25;
    //$mail->SMTPSecure = 'tls';
    $mail->SMTPSecure = 'ssl';
    //$mail->SMTPSecure = false;
    $mail->SMTPAuth = true;
    //$mail->SMTPAuth = false;
    $mail->Username = 'noreply@devloopers.com.br'; // Usuário do servidor SMTP
    $mail->Password = 'abcde12345'; // Senha do servidor SMTP
    //$mail->SMTPDebug = 1;
    
    // Define o remetente
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->From = "noreply@devloopers.com.br"; // Seu e-mail
    $mail->FromName = "Equipe DevLoopers"; // Seu nome
    
    // Define os destinatário(s)
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    //$mail->AddAddress('fulano@dominio.com.br', 'Fulano da Silva');
    $mail->AddAddress($email);
    //$mail->AddCC('ciclano@site.net', 'Ciclano'); // Copia
    //$mail->AddBCC('fulano@dominio.com.br', 'Fulano da Silva'); // Cópia Oculta
    
    // Define os dados técnicos da Mensagem
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
    $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
    
    // Define a mensagem (Texto e Assunto)
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    $mail->Subject = "Recuperação de senha"; // Assunto da mensagem
    $mail->Body = $message;
    $mail->AltBody = "Para visualizar corretamente esta mensagem, utilize um visualizador de e-mail compatível com HTML.";

    // Define os anexos (opcional)
    // =-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
    //$mail->AddAttachment("c:/temp/documento.pdf", "novo_nome.pdf");  // Insere um anexo
    // Envia o e-mail
    $enviado = $mail->Send();
    // Limpa os destinatários e os anexos
    $mail->ClearAllRecipients();
    $mail->ClearAttachments();
    
    // Exibe uma mensagem de resultado
    if ($enviado):
        return true;
    else:
        return false;
    endif;
}

//SEND E-MAIL FUNCTIONS END
//UPDATE LOG FUNCTIONS BEGIN 

function writeFile($message) {
    $file = '/Applications/MAMP/htdocs/dvl_cpanel/app/log.txt';
    //CHECK IF THE FILE IS WRITABLE     
    if (is_writable($file)):
        //TRY TO OPEN
        $handle = fopen($file, 'a+');   //a+ TO READ AND WRITE, WITH THE POINTER AT THE FINAL OF THE FILE
        //IF COULD OPEN
        if ($handle):
            //TRY TO MANIPULATE
            if (fwrite($handle, $message)):
                fclose($handle);
            else:
                fclose($handle);
            endif;
        endif;
    endif;
}

function updateLog($operation, $userAction, $userUpdated, $success) {
    //GET ACTUAL DATE
    date_default_timezone_set('Brazil/East');
    $data = new DateTime();
    $message = "";
    //TREAT $success
    if ($success):
        $success = 'efetuada com sucesso';
    else:
        $success = 'falhou';
    endif;

    switch ($operation) {
        //LOGIN
        case 'login':
            //HAS AN USER BEEN PASSED BY PARAM?
            if(!empty($userAction)):
                //IS THIS USER ACTIVE?
                if($userAction->getActive()):
                    $message = "{$data->format('d-m-Y H:i:s')} - tentativa de login de {$userAction->getEmail()} {$success}\n";
                else:
                    $message = "{$data->format('d-m-Y H:i:s')} - tentativa de login de {$userAction->getEmail()} {$success}. Usuario inativo\n";
                endif;
            else:
                $message = "{$data->format('d-m-Y H:i:s')} - tentativa de login {$success}. Nome de usuario ou senha incorretos ou inexistentes.\n";
            endif;
            break;
        //LOGOUT
        case 'logout':
            $message = "{$data->format('d-m-Y H:i:s')} - tentativa de logout {$success}\n";
            break;
        //FORGOT PASS
        case 'forgotPass':
            if (!empty($userAction)):
                $message = "{$data->format('d-m-Y H:i:s')} - solicitacao de senha do usuario {$userAction->getEmail()} {$success}\n";
            else:
                $message = "{$data->format('d-m-Y H:i:s')} - solicitacao de senha do usuario {$success}\n";
            endif;
            break;
        //CREATE USER
        case 'createUser':
            $message = "{$data->format('d-m-Y H:i:s')} - criacao do usuario {$userUpdated->getEmail()} solicitada pelo usuario {$userAction->getEmail()} {$success}\n";
            break;
        //CHANGE PASS
        case 'changePass':
            $message = "{$data->format('d-m-Y H:i:s')} - alteracao da senha do usuario {$userAction->getEmail()} {$success}\n";
            break;
        //ACTIVATE USER
        case 'activateUser':
            $message = "{$data->format('d-m-Y H:i:s')} - ativar/desativar usuario {$userUpdated->getEmail()} solicitado pelo usuario {$userAction->getEmail()} {$success}\n";
            break;
        //RESET PASS
        case 'resetPass':
            $message = "{$data->format('d-m-Y H:i:s')} - reset da senha do usuario {$userUpdated->getEmail()} solicitada pelo usuario {$userAction->getEmail()} {$success}\n";
            break;
        //DELETE USER
        case 'deleteUser':
            $message = "{$data->format('d-m-Y H:i:s')} - exclusao do usuario {$userUpdated->getEmail()} solicitada pelo usuario {$userAction->getEmail()} {$success}\n";
            break;
        //UPDATE USER
        case 'updateUser':
            $message = "{$data->format('d-m-Y H:i:s')} - atualizacao dos dados do usuario {$userUpdated->getEmail()} solicitada pelo usuario {$userAction->getEmail()} {$success}\n";
            break;
        case 'timeOut':
            $message = "{$data->format('d-m-Y H:i:s')} - a sessao do usuario {$userAction->getEmail()} expirou\n";
            break;
        default:
            //NO ACTION SET
            break;
    }
    
    writeFile($message);
}

//UPDATE LOG FUNCTIONS END

//SHOW USER FUNCTIONS BEGIN

function activateUser($idUser) {
    $user = UserDao::getUserById($idUser);

    //LOGGED USER IS ADM OR TRYING TO ACTIVATE AN NON-ADM USER?
    if (isAdm() && UserDao::getUserById($idUser)->getType() != 0):
        if (!$user->getActive()):
            if (UserDao::updateUserActiveById($user->getId(), true)):
                updateLog('activateUser', UserDao::getUserById($_SESSION["userId"]), $user, true);
                header('Location: ../../controlpanel_functions/result.php?resultMessage=activateUser-activated');
            else:
                updateLog('activateUser', UserDao::getUserById($_SESSION["userId"]), $user, false);
                header('Location: ../../controlpanel_functions/result.php?resultMessage=activateUser-failure');
            endif;
        else:
            if (UserDao::updateUserActiveById($user->getId(), false)):
                updateLog('activateUser', UserDao::getUserById($_SESSION["userId"]), $user, true);
                header('Location: ../../controlpanel_functions/result.php?resultMessage=activateUser-deactivated');
            else:
                updateLog('activateUser', UserDao::getUserById($_SESSION["userId"]), $user, false);
                header('Location: ../../controlpanel_functions/result.php?resultMessage=activateUser-failure');
            endif;

        endif;
    else:
        updateLog('activateUser', UserDao::getUserById($_SESSION["userId"]), $user, false);
        header('Location: ../../controlpanel_functions/result.php?resultMessage=activateUser-noPermission');
    endif;
}

function resetPass($idUser) {
    $user = UserDao::getUserById($idUser);

    if (isAdm()):
        //TRY TO UPDATE
        if (UserDao::updateUserPassById($user->getId(), md5('0000'))):
            updateLog('resetPass', UserDao::getUserById($_SESSION["userId"]), $user, true);
            header('Location: ../../controlpanel_functions/result.php?resultMessage=resetPass-success');
        else:
            updateLog('resetPass', UserDao::getUserById($_SESSION["userId"]), $user, false);
            header('Location: ../../controlpanel_functions/result.php?resultMessage=resetPass-failure');
        endif;
    else:
        updateLog('resetPass', UserDao::getUserById($_SESSION["userId"]), $user, false);
        header('Location: ../../controlpanel_functions/result.php?resultMessage=resetPass-noPermission');
    endif;
}

function deleteUser($idUser) {
    $user = UserDao::getUserById($idUser);
    //LOGGED USER IS ADM OR TRYING TO DELETE AN NON-ADM USER?
    if (isAdm() && ($user->getType() != 0)):
        //TRY TO DELETE
        if (UserDao::deleteUserById($idUser)):
            updateLog('deleteUser', UserDao::getUserById($_SESSION["userId"]), $user, true);
            header('Location: ../../controlpanel_functions/result.php?resultMessage=deleteUser-success');
        else:
            updateLog('deleteUser', UserDao::getUserById($_SESSION["userId"]), $user, false);
            header('Location: ../../controlpanel_functions/result.php?resultMessage=deleteUser-failure');
        endif;
    else:
        updateLog('deleteUser', UserDao::getUserById($_SESSION["userId"]), $user, false);
        header('Location: ../../controlpanel_functions/result.php?resultMessage=deleteUser-noPermission');
    endif;
}

//SHOW USER FUNCTIONS END

