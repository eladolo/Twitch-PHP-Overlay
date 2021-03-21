
window.base64urlEncode = function(source){
    // Encode in classical base64
    encodedSource = CryptoJS.enc.Base64.stringify(source);

    // Remove padding equal characters
    encodedSource = encodedSource.replace(/=+$/, '');

    // Replace characters according to base64url specifications
    encodedSource = encodedSource.replace(/\+/g, '-');
    encodedSource = encodedSource.replace(/\//g, '_');

    return encodedSource;
};

window.decodeJWT = function(jwt){
    var base64Url = jwt.split('.')[1];
    var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
    return JSON.parse(window.atob(base64));
};

window.signJWT = function(data, secret){
    secret = (typeof secret != "undefined") ? secret : app.public_secret;
    var jwt = '';
    // Defining our token parts
    var header = {
        "alg": "HS256",
        "typ": "JWT"
    };

    var stringifiedHeader = CryptoJS.enc.Utf8.parse(JSON.stringify(header));
    var encodedHeader = base64urlEncode(stringifiedHeader);

    var stringifiedData = CryptoJS.enc.Utf8.parse(JSON.stringify(data));
    var encodedData = base64urlEncode(stringifiedData);

    var signature = encodedHeader + "." + encodedData;
    signature = CryptoJS.HmacSHA256(signature, secret);
    signature = base64urlEncode(signature);
    jwt = encodedHeader + "." + encodedData + "." + signature;

    return jwt;
};

window.verifyJWT = function(jwt, secret){
    secret = (typeof secret != "undefined") ? secret : app.public_secret;
    var res = false;

    jwt = jwt.split('.');
    var signature = jwt[2];
    var tmp_signature = jwt[0] + "." + jwt[1];
    tmp_signature = CryptoJS.HmacSHA256(tmp_signature, secret);
    tmp_signature = base64urlEncode(tmp_signature);

    if(tmp_signature === signature){
        res = true;
    }

    return res;
};
