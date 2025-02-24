<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Podgląd Obrazków z Paskiem Postępu i Usuwaniem</title>
  <style>
    .preview-container {
      margin-top: 20px;
      display: flex;
      gap: 15px;
      flex-wrap: wrap;
    }
    .image-wrapper {
      position: relative;
      width: 120px;
      text-align: center;
    }
    .image-wrapper img {
      max-width: 100px;
      max-height: 100px;
      border-radius: 8px;
      border: 2px solid #ccc;
    }
    .progress-bar {
      width: 100%;
      background-color: #f3f3f3;
      border-radius: 5px;
      overflow: hidden;
      height: 8px;
      margin-top: 5px;
    }
    .progress {
      width: 0%;
      height: 100%;
      background-color: #4caf50;
      transition: width 0.2s ease;
    }
    .error-message {
      color: red;
      font-size: 14px;
      margin-top: 10px;
    }
    .remove-btn {
      position: absolute;
      top: -8px;
      right: -8px;
      background-color: red;
      color: white;
      border: none;
      border-radius: 50%;
      width: 20px;
      height: 20px;
      cursor: pointer;
      font-size: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>
</head>
<body>

  <h2>Wybierz Obrazki (max. 5 MB, PNG/JPG) z Usuwaniem 🗑️📤</h2>

  <input type="file" id="fileInput" accept="image/jpeg, image/png" multiple />

  <div class="error-message" id="errorMessage"></div>

  <div class="preview-container" id="previewContainer"></div>

  <form id="emailForm" action="send_email.php" method="POST" style="display: none;">
    <div id="fileNamesContainer"></div>
    <input type="email" name="email" placeholder="Twój email" required>
    <button type="submit">Wyślij Email</button>
  </form>

  <script>
    const fileInput = document.getElementById('fileInput');
    const previewContainer = document.getElementById('previewContainer');
    const errorMessage = document.getElementById('errorMessage');
    const emailForm = document.getElementById('emailForm');
    const fileNamesContainer = document.getElementById('fileNamesContainer');

    const uploadedFiles = [];
    const MAX_FILES = 2;

    fileInput.addEventListener('change', (event) => {
      const files = event.target.files;
      errorMessage.textContent = '';
      console.log('Liczba przesłanych plików przed dodaniem:', uploadedFiles.length);

      if (uploadedFiles.length + files.length > MAX_FILES) {
        errorMessage.textContent = `Możesz przesłać maksymalnie ${MAX_FILES} obrazki.`;
        fileInput.value = '';
        return;
      }

      Array.from(files).forEach((file) => {
        if (file.size > 5 * 1024 * 1024) {
          errorMessage.textContent = 'Rozmiar pliku nie może przekraczać 5 MB!';
          fileInput.value = '';
          return;
        }

        if (!['image/jpeg', 'image/png'].includes(file.type)) {
          errorMessage.textContent = 'Tylko pliki JPG, JPEG i PNG są dozwolone!';
          fileInput.value = '';
          return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
          const imageWrapper = document.createElement('div');
          imageWrapper.classList.add('image-wrapper');

          const img = document.createElement('img');
          img.src = e.target.result;
          imageWrapper.appendChild(img);

          const progressBar = document.createElement('div');
          progressBar.classList.add('progress-bar');

          const progress = document.createElement('div');
          progress.classList.add('progress');
          progressBar.appendChild(progress);

          imageWrapper.appendChild(progressBar);

          const removeBtn = document.createElement('button');
          removeBtn.classList.add('remove-btn');
          removeBtn.innerHTML = '❌';
          removeBtn.addEventListener('click', () => removeImage(imageWrapper, file));
          imageWrapper.appendChild(removeBtn);

          previewContainer.appendChild(imageWrapper);

          uploadImage(file, progress, imageWrapper);
        };

        reader.readAsDataURL(file);
      });

      if (uploadedFiles.length + files.length >= MAX_FILES) {
        fileInput.disabled = true;
      }
    });

    function uploadImage(file, progressElement, imageWrapper) {
      const formData = new FormData();
      formData.append('image', file);

      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload.php', true);

      xhr.upload.addEventListener('progress', (e) => {
        if (e.lengthComputable) {
          const percentComplete = (e.loaded / e.total) * 100;
          progressElement.style.width = percentComplete + '%';
        }
      });

      xhr.onload = function() {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            console.log('Obrazek przesłany:', response.file);
            progressElement.style.backgroundColor = '#4caf50';
            uploadedFiles.push({ filePath: response.file, wrapper: imageWrapper });

            const fileNameInput = document.createElement('input');
            fileNameInput.type = 'hidden';
            fileNameInput.name = 'fileNames[]';
            fileNameInput.value = response.file;
            fileNamesContainer.appendChild(fileNameInput);

            emailForm.style.display = 'block';
          } else {
            console.error('Błąd:', response.error);
            progressElement.style.backgroundColor = 'red';
          }
        } else {
          console.error('Błąd podczas przesyłania:', xhr.statusText);
          progressElement.style.backgroundColor = 'red';
        }
      };

      xhr.onerror = function() {
        console.error('Błąd połączenia');
        progressElement.style.backgroundColor = 'red';
      };

      xhr.send(formData);
    }

    function removeImage(imageWrapper, file) {
      imageWrapper.remove();

      const uploadedFile = uploadedFiles.find(item => item.wrapper === imageWrapper);
      if (uploadedFile) {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'delete.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onload = function() {
          if (xhr.status === 200) {
            console.log('Plik usunięty z serwera:', uploadedFile.filePath);
          } else {
            console.error('Błąd podczas usuwania pliku:', xhr.statusText);
          }
        };
        xhr.send(JSON.stringify({ filePath: uploadedFile.filePath }));

        const index = uploadedFiles.indexOf(uploadedFile);
        if (index > -1) {
          uploadedFiles.splice(index, 1);
        }

        const fileNameInputs = fileNamesContainer.querySelectorAll('input[name="fileNames[]"]');
        fileNameInputs.forEach(input => {
          if (input.value === uploadedFile.filePath) {
            input.remove();
          }
        });
      }

      if (uploadedFiles.length < MAX_FILES) {
        fileInput.disabled = false;
      }

      if (uploadedFiles.length === 0) {
        emailForm.style.display = 'none';
      }
    }
  </script>

</body>
</html>
