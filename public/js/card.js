function printCard() {
  const card = document.querySelector('.id-card.front');
  if (!card) {
    alert('Card not found!');
    return;
  }

  // Create hidden iframe for printing
  const iframe = document.createElement('iframe');
  iframe.style.position = 'fixed';
  iframe.style.width = '0';
  iframe.style.height = '0';
  iframe.style.border = '0';
  document.body.appendChild(iframe);

  const doc = iframe.contentWindow.document;
  const styles = Array.from(document.querySelectorAll('link[rel="stylesheet"], style'))
    .map(link => link.outerHTML)
    .join('\n');

  doc.open();
  doc.write(`
    <html>
      <head>
        <title>Print ID Card</title>
        ${styles}
        <style>
          @page { size: 3.37in 2.125in landscape; margin: 0; }
          body { margin: 0; display: flex; justify-content: center; align-items: center; }
          .id-card { width: 3.37in; height: 2.125in; }
        </style>
      </head>
      <body>${card.outerHTML}</body>
    </html>
  `);
  doc.close();

  // Wait for images to load, then print
  const imgs = doc.images;
  let loaded = 0;
  for (let img of imgs) {
    img.onload = img.onerror = () => {
      loaded++;
      if (loaded === imgs.length) iframe.contentWindow.print();
    };
  }
  if (imgs.length === 0) iframe.contentWindow.print();

  setTimeout(() => document.body.removeChild(iframe), 3000);
}
