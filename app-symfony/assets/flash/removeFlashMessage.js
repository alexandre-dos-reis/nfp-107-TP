const alerts = Array.from(document.querySelectorAll(".alert"));

if (alerts.length > 0) {
  alerts.map((alert) => {
    setTimeout(() => {
      alert.parentNode.removeChild(alert);
    }, 5000);
  });
}
