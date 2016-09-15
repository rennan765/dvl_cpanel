function logOut() {
    if (confirm("Gostaria realmente de efetuar o log-off?")) {
        window.location.href = 'app/control/controlLogOut.php';
    }
}

function confirmDeleteUser() {
    if (confirm("Gostaria realmente de excluir este usuário?")) {
        document.getElementByName('deleteUser').submit();
    }
}