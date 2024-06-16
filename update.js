addEventListener('fetch', event => {
  event.respondWith(handleRequest(event.request));
});

async function handleRequest(request) {
  try {
    // Fetch data from the PHP endpoint
    const response = await fetch('https://captaintvwc.x10.mx/tataplay/tatajson.php');
    
    // Check if the response is ok
    if (!response.ok) {
      throw new Error('Network response was not ok');
    }

    const data = await response.json();

    // Check if data structure is as expected
    if (!data || !data.data || !Array.isArray(data.data.channels)) {
      throw new Error('Unexpected data structure');
    }

    // Extract the required information
    const extractedData = data.data.channels.map(channel => {
      return {
        id: channel.id,
        keyId: channel.clearkeys ? channel.clearkeys.keyId : null,
        key: channel.clearkeys ? channel.clearkeys.key : null
      };
    });

    // Filter out entries without clearkeys
    const filteredData = extractedData.filter(item => item.keyId && item.key);

    // Return the filtered data as JSON
    return new Response(JSON.stringify(filteredData), {
      headers: { 'Content-Type': 'application/json' }
    });
  } catch (error) {
    // Handle any errors that occurred during the fetch or processing
    return new Response(JSON.stringify({ error: error.message }), {
      headers: { 'Content-Type': 'application/json' },
      status: 500
    });
  }
}
