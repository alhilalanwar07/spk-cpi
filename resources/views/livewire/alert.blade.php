<div>
    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('tambahAlert', (event) => {
                const data = event;
                Swal.fire({
                    title: data[0]['title']
                    , text: data[0]['text']
                    , icon: data[0]['type']
                    , timer: data[0]['timeout']
                    , timerProgressBar: true
                , });
                setTimeout(function() { window.location.reload(); }, data[0]['timeout']);
            });
        });

        document.addEventListener('livewire:init', () => {
            Livewire.on('updateAlert', (event) => {
                const data = event;
                Swal.fire({
                    title: data[0]['title']
                    , text: data[0]['text']
                    , icon: data[0]['type']
                    , timer: data[0]['timeout']
                    , timerProgressBar: true
                , });
                setTimeout(function() { window.location.reload(); }, data[0]['timeout']);
            });


        });

        document.addEventListener('livewire:init', () => {
            Livewire.on('hapusAlert', (event) => {
                const data = event;
                Swal.fire({
                    title: data[0]['title']
                    , text: data[0]['text']
                    , icon: data[0]['type']
                    , timer: data[0]['timeout']
                    , timerProgressBar: true
                , });
                setTimeout(function() { window.location.reload(); }, data[0]['timeout']);
            });
        });

        document.addEventListener('livewire:init', () => {
            //updateAlertToast
            Livewire.on('updateAlertToast', (event) => {
                const data = event;
                Swal.fire({
                    title: data[0]['title']
                    , text: data[0]['text']
                    , icon: data[0]['type']
                    , timer: data[0]['timeout']
                    , timerProgressBar: true
                , });

            });
        });

        document.addEventListener('livewire:init', function() {
            Livewire.on('openModal', () => {
                const modal = new bootstrap.Modal(document.getElementById('modalTambah'));
                modal.show();
            });

            Livewire.on('closeModal', () => {
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalTambah'));
                modal.hide();
            });
        });
    </script>
</div>