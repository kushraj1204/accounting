(function () {
    var testTool = window.testTool;
    // a tool use debug mobile device
    if (testTool.isMobileDevice()) {
        vConsole = new VConsole();
    }
    console.log(JSON.stringify(ZoomMtg.checkSystemRequirements()));

    var meetingConfig = window.meetingConfig;
    console.log("meetingConfig", meetingConfig);

    // it's option if you want to change the WebSDK dependency link resources. setZoomJSLib must be run at first
    // ZoomMtg.setZoomJSLib("https://source.zoom.us/1.7.10/lib", "/av"); // CDN version defaul
    if (meetingConfig.china)
        ZoomMtg.setZoomJSLib("https://jssdk.zoomus.cn/1.7.10/lib", "/av"); // china cdn option
    ZoomMtg.preLoadWasm();
    ZoomMtg.prepareJssdk();

    function beginJoin(signature) {
        ZoomMtg.init({
            leaveUrl: meetingConfig.leaveUrl,
            webEndpoint: meetingConfig.webEndpoint,
            success: function () {
                //$.i18n.reload(meetingConfig.lang);
                ZoomMtg.join({
                    meetingNumber: meetingConfig.meetingNumber,
                    userName: meetingConfig.userName,
                    signature: signature,
                    apiKey: meetingConfig.apiKey,
                    userEmail: meetingConfig.userEmail,
                    passWord: meetingConfig.passWord,
                    success: function (res) {
                        ZoomMtg.getAttendeeslist({});
                        ZoomMtg.getCurrentUser({
                            success: function (res) {
                                console.log("success getCurrentUser", res.result.currentUser);
                            },
                        });
                    },
                    error: function (res) {
                        console.log(res);
                    },
                });
            },
            error: function (res) {
                console.log(res);
            },
        });
    }

    beginJoin(meetingConfig.signature);
})();
