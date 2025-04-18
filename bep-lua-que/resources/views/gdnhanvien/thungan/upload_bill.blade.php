<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lưu Hóa Đơn</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FilePond CSS -->
    <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
    <!-- FilePond Image Preview CSS -->
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>
    <!-- FilePond Image Transform -->
    <script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
    <!-- Tesseract.js -->
    <script src="https://unpkg.com/tesseract.js@4.1.2/dist/tesseract.min.js"></script>
    <!-- Jimp -->
    <script src="https://unpkg.com/jimp@0.16.1/browser/lib/jimp.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Tùy chỉnh FilePond */
        .filepond--root {
            @apply bg-white border border-gray-200 rounded-xl shadow-md;
        }
        .filepond--drop-label {
            @apply text-gray-600 text-center font-medium;
        }
        .filepond--panel-root {
            @apply bg-gradient-to-b from-gray-50 to-white;
        }
        .filepond--item-panel {
            @apply bg-gray-100 border border-gray-200 rounded-lg;
        }
        .filepond--file-action-button {
            @apply bg-indigo-500 hover:bg-indigo-600 text-white;
        }
        /* Tùy chỉnh background */
        body {
            background: linear-gradient(135deg, #e0f2fe, #dbeafe);
        }
        /* Tùy chỉnh thông báo */
        #notification {
            display: none;
            @apply fixed bottom-4 right-4 p-4 rounded-lg shadow-lg text-white;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="container max-w-md mx-auto bg-white p-8 rounded-2xl shadow-xl">
        <!-- Tiêu đề -->
        <h1 class="text-2xl font-bold text-center text-indigo-700 mb-6">Lưu Hóa Đơn</h1>
        <!-- Ô tìm mã hóa đơn -->
        <div class="mb-8">
            <label for="invoiceCode" class="block text-sm font-medium text-gray-700 mb-2">Mã hóa đơn</label>
            <div class="flex space-x-3">
                <input 
                    type="text" 
                    id="invoiceCode" 
                    placeholder="Nhập mã hóa đơn" 
                    class="flex-1 p-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300 bg-gray-50"
                >
                <button 
                    onclick="searchInvoice()" 
                    class="px-5 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300 transform hover:scale-105"
                >
                    Tìm
                </button>
                <button 
                    onclick="saveInvoice()" 
                    class="px-5 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-300 transform hover:scale-105"
                >
                    Lưu
                </button>
            </div>
        </div>
        <!-- Ô chọn ảnh -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tải ảnh hóa đơn</label>
            <input type="file" id="filepond" accept="image/*">
        </div>
    </div>

    <!-- Thông báo -->
    <div id="notification"></div>

    <!-- FilePond JS -->
    <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
    <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>
    <script>
        // Hàm hiển thị thông báo
        function showNotification(message, type = 'error') {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = `fixed bottom-4 right-4 p-4 rounded-lg shadow-lg text-white ${type === 'error' ? 'bg-red-500' : 'bg-green-500'}`;
            notification.style.display = 'block';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 3000);
        }

        // Đăng ký plugin
        FilePond.registerPlugin(FilePondPluginImagePreview, FilePondPluginImageTransform);

        // Khởi tạo FilePond
        const pond = FilePond.create(document.querySelector('#filepond'), {
            allowMultiple: false,
            acceptedFileTypes: ['image/*'],
            labelIdle: 'Kéo & thả ảnh hoặc <span class="filepond--label-action">Chọn ảnh</span>',
            imagePreviewHeight: 150,
            stylePanelLayout: 'compact',
            styleItemPanelAspectRatio: '1',
            imageTransformOutputQuality: 100,
            imageTransformOutputMimeType: 'image/jpeg',
            onaddfile: async (error, file) => {
                if (error) {
                    console.error('Lỗi khi chọn ảnh:', error);
                    showNotification('Lỗi khi chọn ảnh.');
                    return;
                }
                console.log('Ảnh đã chọn:', file.file);

                // Tiền xử lý ảnh
                let imageData = file.file;
                try {
                    const image = await Jimp.read(URL.createObjectURL(file.file));
                    image.contrast(0.3).brightness(0.1).normalize();
                    imageData = await image.getBufferAsync(Jimp.MIME_JPEG);
                } catch (jimpError) {
                    console.error('Lỗi khi xử lý ảnh với Jimp:', jimpError);
                }

                // Trích xuất văn bản
                try {
                    const { createWorker } = Tesseract;
                    const worker = await createWorker({
                        langPath: 'https://tessdata.projectnaptha.com/4.0.0',
                        logger: m => console.log(m),
                    });
                    await worker.loadLanguage('eng');
                    await worker.initialize('eng');
                    await worker.setParameters({
                        tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-',
                        tessedit_ocr_engine_mode: 3,
                        tessedit_pageseg_mode: 6
                    });
                    const { data: { text } } = await worker.recognize(imageData);
                    await worker.terminate();

                    console.log('Văn bản trích xuất:', text);
                    console.log('Các biến thể thử nghiệm:', text.match(/(?:BQL\s*)?HD\s*-\s*\d{8}\s*-\s*[A-Z0-9]{4}/i), text.match(/T?HD\d{8}[A-Z0-9]{4}/i));

                    // Tìm mã hóa đơn
                    const invoiceCodeMatch = text.match(/(?:BQL\s*)?HD\s*-\s*\d{8}\s*-\s*[A-Z0-9]{4}|T?HD\d{8}[A-Z0-9]{4}/i);
                    let invoiceCode = invoiceCodeMatch ? invoiceCodeMatch[0].replace(/\s/g, '') : '';

                    // Xử lý lỗi OCR
                    if (invoiceCode) {
                        invoiceCode = invoiceCode.replace(/^(BQL|T)/, '');
                        if (!invoiceCode.includes('-') && invoiceCode.startsWith('HD')) {
                            invoiceCode = `HD-${invoiceCode.slice(2, 10)}-${invoiceCode.slice(10)}`;
                        }
                    }

                    // Điền mã hóa đơn
                    if (invoiceCode) {
                        document.getElementById('invoiceCode').value = invoiceCode;
                        console.log('Mã hóa đơn tìm được:', invoiceCode);
                        showNotification('Đã tìm thấy mã hóa đơn: ' + invoiceCode, 'success');
                    } else {
                        console.warn('Không tìm thấy mã hóa đơn trong ảnh');
                        showNotification('Không tìm thấy mã hóa đơn trong ảnh. Vui lòng nhập thủ công.');
                    }
                } catch (err) {
                    console.error('Lỗi khi trích xuất văn bản:', err);
                    showNotification('Lỗi khi xử lý ảnh. Vui lòng thử lại.');
                }
            }
        });

        // Hàm tìm kiếm hóa đơn
        function searchInvoice() {
            const invoiceCode = document.getElementById('invoiceCode').value;
            console.log("Đang tìm mã hóa đơn:", invoiceCode);
        }

        // Hàm lưu hóa đơn
        function saveInvoice() {
            const invoiceCode = document.getElementById('invoiceCode').value;
            const file = pond.getFile();

            if (!invoiceCode) {
                showNotification('Vui lòng nhập mã hóa đơn.');
                return;
            }
            if (!file) {
                showNotification('Vui lòng chọn ảnh hóa đơn.');
                return;
            }

            const formData = new FormData();
            formData.append('invoiceCode', invoiceCode);
            formData.append('image', file.file);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: 'save-bill-image',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        showNotification('Lưu hóa đơn thành công!', 'success');
                        // Reset giao diện
                        document.getElementById('invoiceCode').value = '';
                        pond.removeFile();
                    } else {
                        showNotification(response.message || 'Không tìm thấy hóa đơn.', 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Lỗi khi lưu hóa đơn:', xhr);
                    showNotification('Lỗi khi lưu hóa đơn. Vui lòng thử lại.', 'error');
                }
            });
        }
    </script>
</body>
</html>