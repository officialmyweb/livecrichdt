// config.js

const videoConfigs = {
    "/sky": {
        manifestUri: "https://fsly.stream.peacocktv.com/Content/CMAF_CTR-4s/Live/channel(lc107a1ddy)/master.mpd",
        drmKeys: {
            '002007110c69a23803173b50eab05f23': '590d6e8f4ca81319f9bb29104f571990;'
        }
    },
    "/sky2": {
        manifestUri: "https://webtvstream.bhtelecom.ba/hls6/as_premium1.mpd",
        drmKeys: {
            'c18b6aa739be4c0b774605fcfb5d6b68': 'e41c3a6f7532b2e3a828d9580124c89d;'
        }
    }
};

// Function to get video configuration based on path
function getVideoConfig(path) {
    return videoConfigs[path] || null;
}
