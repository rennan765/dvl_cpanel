function logOut() {
	if (confirm("Gostaria realmente de efetuar o log-off?")) {
		window.location.href = 'app/control/logOut.php';
	}
}

function confirmDeleteUser() {
	if (confirm("Gostaria realmente de excluir este usuário?")) {
		document.showUserForm.submit();
	} else{
		window.location.href = 'showUsers.php';
	}
}