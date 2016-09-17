function logOut() {
    if (confirm("Gostaria realmente de efetuar o log-off?")) {
        window.location.href = 'app/control/controlLogOut.php';
    }
}

function confirmDeleteUser() {
    if (confirm("Gostaria realmente de excluir este usuário?")) {
        document.getElementById('showUsersForm').submit();
    }
}

function sendForm(operation) {
    switch (operation) {
        case 1:
            document.showUserForm.action = '../app/control/controlActivateUser.php';
            break;
        case 2:
            document.showUserForm.action = '../app/control/controlResetPass.php';
            break;
        default:
            //NO ACTION SET
            break;
    }

    document.getElementById('showUsersForm').submit();
}