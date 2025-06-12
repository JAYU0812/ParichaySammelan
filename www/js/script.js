document.addEventListener('DOMContentLoaded', () => {
  const sameAddress = document.getElementById('sameAddress');
  const permAddressSection = document.getElementById('permanentAddressSection');
  const form = document.getElementById('userForm');
  const progressBar = document.getElementById('progressBar');
  const adminBtn = document.getElementById('adminAccessBtn');

  sameAddress.addEventListener('change', () => {
    if (sameAddress.checked) {
      permAddressSection.style.display = 'none';
    } else {
      permAddressSection.style.display = 'block';
    }
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    progressBar.style.width = '0%';
    progressBar.style.display = 'block';

    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'submit_form.php', true);

    xhr.upload.onprogress = (e) => {
      if (e.lengthComputable) {
        const percent = (e.loaded / e.total) * 100;
        progressBar.style.width = percent + '%';
      }
    };

    xhr.onload = () => {
      if (xhr.status === 200) {
        alert('Form submitted! Check your email for the unique ID.');
        form.reset();
        progressBar.style.width = '0%';
      } else {
        alert('Submission failed.');
      }
    };

    xhr.send(formData);
  });

  adminBtn.addEventListener('click', () => {
    window.location.href = 'admin.html';
  });
});
