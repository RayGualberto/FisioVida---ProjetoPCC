function mostrarMensagem(msg, sucesso = true) {
    Toastify({
        text: msg,
        duration: 3000,
        close: true,
        gravity: "top",
        position: "right",
        backgroundColor: sucesso
            ? "linear-gradient(to right, #0b8ecb)"
            : "linear-gradient(to right, #ff0015ff)",
    }).showToast();
}
