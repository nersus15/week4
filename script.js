function validasiForm() {
    const nama = document.getElementById('nama').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (nama.trim() === "" || email.trim() === "" || password.trim() === "") {
        alert("Semua field harus diisi!");
        return false;
    }

    if (password.length < 6) {
        alert("Password minimal harus 6 karakter!");
        return false;
    }

    console.log("Validasi berhasil, data dikirim ke server.");
    return true;
}

function validasiFormUpdate() {
    const nama = document.getElementById('nama').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if (nama.trim() === "" || email.trim() === "") {
        alert("Semua field harus diisi!");
        return false;
    }

    if (password && password.length < 6) {
        alert("Password minimal harus 6 karakter!");
        return false;
    }

    console.log("Validasi berhasil, data dikirim ke server.");
    return true;
}

function hapusdata(e) {
    var id = e.currentTarget.dataset.id; // .id disini sesuai dengan data-id di tombol delete, jika di tombol delete menuliskan data-ada maka disini harus dataset.ada 
    var yatidak = confirm('Yakin ingin menghapus data dengan ID ' + id + ' ?');
    if (!yatidak) return;
    kirimasync('delete_user.php', {id: id});
}

function kirimasync($url, data = {}) {
    var xhr = new XMLHttpRequest();

    xhr.open('POST', $url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    // Handle response
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) { // Request completed
            if (xhr.status >= 200 && xhr.status < 300) {
                // SUCCESS: Status 200-299
                handleSuccess(xhr);
            } else {
                // ERROR: Status lain (400, 401, 403, 404, 500, dll)
                handleError(xhr);
            }
        }
    };


    xhr.send(JSON.stringify(data));

    // ===== SUCCESS HANDLER =====
    function handleSuccess(xhr) {
        try {
            const response = JSON.parse(xhr.responseText);

            // Tampilkan alert sukses
            showAlert('Hapus data', response.message || 'Operation completed successfully', 'berhasil');
            location.reload();
        } catch (error) {
            // Handle JSON parse error
            console.error('Error parsing response:', error);
            showAlert('Error', 'Invalid response from server', 'error');
        }
    }

    // ===== ERROR HANDLER =====
    function handleError(xhr) {
        try {
            const response = JSON.parse(xhr.responseText);
            // Tampilkan error message dari server
            showAlert('Hapus data', response.message || `Error ${xhr.status}: ${xhr.statusText}`, 'gagal');
        } catch (error) {
            // Handle case where response is not JSON
            showAlert('Error', `Error ${xhr.status}: ${xhr.statusText}`, 'error');
        }
    }

    // ===== HELPER FUNCTIONS =====
    function showAlert(title, message, type) {
        // Ganti dengan alert system Anda
        alert(`${type.toUpperCase()}: ${title}\n${message}`);

        // Atau menggunakan library alert/toast
        // Toastify({ text: message, className: type }).showToast();
    }

}