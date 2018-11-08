#import "ApprewarderAppDelegate.h"
#import "WebViewJavascriptBridge.h"
#import "AlertView.h"
#import "Reachability.h"
#import "Flurry.h"
#import <Parse/Parse.h>
#import <FacebookSDK/FacebookSDK.h>
#import <FacebookSDK/FBSessionTokenCachingStrategy.h>
#import <AdSupport/AdSupport.h>
#import "APLViewController.h"
#include <sys/socket.h>
#include <sys/sysctl.h>
#include <net/if.h>
#include <net/if_dl.h>
#import <sys/utsname.h>
//#import <MobileAppTracker/MobileAppTracker.h>
#import <AdSupport/AdSupport.h>






@implementation ApprewarderAppDelegate

@synthesize window = _window;
@synthesize javascriptBridge = bridge;
static NSString __strong *MODE = @"LOCAL"; //LOCAL STAGE PROD
static NSString __strong *LOCAL_IP = @"127.0.0.1"; //127.0.0.1


static NSString *serverURL;
static NSString *serverRoot;
static NSString __strong *deviceID;
static NSString __strong *userID;
static NSUserDefaults *defaults;
static NSString *clientVersion = @"1.1";
static NSString *referrerID = @"";
static NSString *referrerSource = @"0";
static NSString *fbName;
static NSString *fbEmail;
static NSString *fbToken;
static NSString *fbPhoto;
static NSString *fbGender;
static NSString *fbLocale;
static NSString *fbVerified;
static NSString *fbUserID;
static NSString *userAgent;
static NSString *isJailbroken;
static BOOL *inBackground = NO;
static UIWebView *_webView;
static bool *isReferred;
static bool *isLoading = NO;
static bool *didInitialLaunch;
static bool *didRegisterFacebook = NO;
static UIActivityIndicatorView *spinner;





- (void)webViewDidStartLoad:(UIWebView *)webView {
    
    if(!isLoading) {
        NSLog(@"webViewDidStartLoad");
        [self startLoadingAnimationWithMessage:@"Loading.." tag:1234 _view:webView];
        isLoading = YES;
    }
}

- (void)webViewDidFinishLoad:(UIWebView *)webView {
    if(isLoading)
    {
        NSLog(@"webViewDidFinishLoad");
        [self stopLoadingAnimationWithTag:1234 _view:webView];
        isLoading = NO;
    }
    
}

- (BOOL) isFacebooklogin {
    if (FBSession.activeSession.state == FBSessionStateOpen
        || FBSession.activeSession.state == FBSessionStateOpenTokenExtended) return true; else return false;
}

- (BOOL)webView:(UIWebView *)webView shouldStartLoadWithRequest:(NSURLRequest *)request navigationType:(UIWebViewNavigationType)navigationType {
    NSLog(@"URL query: %@%@%@",request.URL.host,request.URL.path,request.URL.query);
    NSString *domain = request.URL.host;
    if([domain isEqualToString:@"127.0.0.1"] ||
       [domain isEqualToString:@"m.apprewarder.com"] ||
       [domain isEqualToString:@"mstage.apprewarder.com"] ||
       [domain isEqualToString:@"forum.apprewarder.com"] ||
       [domain isEqualToString:@"forum.stage.apprewarder.com"] ||
       [domain isEqualToString:@"forum.apprewarder"] ||
       [domain isEqualToString:@"m.apprewarder"] ||
       [domain isEqualToString:LOCAL_IP]
       ) {
        return YES;
    } else {
        [[UIApplication sharedApplication] openURL:request.URL];
    }
    return YES;
}

- (void)alertView:(UIAlertView *)alertView didDismissWithButtonIndex:(NSInteger)buttonIndex
{
    exit(0);
}

- (void)loadCredentials {
    NSLog(@"Load credentials ..");
    userID = [defaults valueForKey:@"userID"];
    deviceID = [defaults valueForKey:@"deviceID"];
    isReferred = ([[defaults valueForKey:@"isReferred"] isEqualToString:@"1"])?YES:NO;
    didInitialLaunch = ([[defaults valueForKey:@"didInitialLaunch"] isEqualToString:@"1"])?YES:NO;
    didRegisterFacebook = ([[defaults valueForKey:@"didRegisterFacebook"] isEqualToString:@"1"])?YES:NO;
    fbName = [defaults valueForKey:@"fbName"];
    fbEmail = [defaults valueForKey:@"fbEmail"];
    fbToken = [defaults valueForKey:@"fbToken"];
    fbPhoto = [defaults valueForKey:@"fbPhoto"];
    fbUserID = [defaults valueForKey:@"fbUserID"];
    fbLocale = [defaults valueForKey:@"fbLocale"];
    fbGender = [defaults valueForKey:@"fbGender"];
    fbVerified = [defaults valueForKey:@"fbVerified"];
    isJailbroken = [defaults valueForKey:@"isJailBroken"];
    //lets makes sure this phone is not jail broken
    if([self isJailbroken]) { [defaults setValue:@"1" forKey:@"isJailBroken"]; }

    
    
    NSLog(@"didInitialLaunch: %d",(int)didInitialLaunch);
    NSLog(@"isReferred: %d",(int)isReferred);
    if(!isReferred) {
        referrerID = [defaults valueForKey:@"referrerID"];
        referrerSource = [defaults valueForKey:@"referrerSource"];
    }
}


- (BOOL)isRegistered {
    
    if ([[NSString stringWithFormat:@"%@",userID] length ]== 0 || deviceID.length == 0 ) return false; else return true;
}

- (void) setFbName :(NSString*) _id {
    [defaults setValue:_id forKey:@"fbName"];
    fbName = _id;
    NSLog(@"Facebook name set to: %@",_id);
}

- (void) setFbVerified :(NSString*) _id {
    [defaults setValue:_id forKey:@"fbVerified"];
    fbVerified = _id;
    NSLog(@"Facebook verification status set to %@",_id);
}
- (void) setFbEmail :(NSString*) _id {
    [defaults setValue:_id forKey:@"fbEmail"];
    fbEmail = _id;
    NSLog(@"Facebook email set to: %@",_id);
}
- (void) setFbToken :(NSString*) _id {
    [defaults setValue:_id forKey:@"fbToken"];
    fbToken = _id;
    NSLog(@"Facebook token set to: %@",_id);
}

- (void) setFbPhoto :(NSString*) _id {
    [defaults setValue:_id forKey:@"fbPhoto"];
    fbPhoto = _id;
    NSLog(@"Facebook photo url set to: %@",_id);
}

- (void) setFbUserID :(NSString*) _id {
    [defaults setValue:_id forKey:@"fbUserID"];
    fbUserID = _id;
    NSLog(@"Facebook user id set to: %@",_id);
}

- (void) setFbGender :(NSString*) _id {
    [defaults setValue:_id forKey:@"fbGender"];
    fbGender= _id;
    NSLog(@"Facebook gender set to: %@",_id);
}

- (void) setFbLocale :(NSString*) _id {
    [defaults setValue:_id forKey:@"fbLocale"];
    fbLocale = _id;
    NSLog(@"Facebook locale set to: %@",_id);
}

- (void) setUserID :(NSString*) _id {
    [defaults setValue:_id forKey:@"userID"];
    userID = _id;
    NSLog(@"userID set to: %@",_id);
}

- (void) setDeviceID :(NSString*) _id {
    [defaults setValue:_id forKey:@"deviceID"];
    deviceID = _id;
    NSLog(@"deviceID set to: %@",_id);
}

- (NSString*) getUserAgent {
    UIWebView *wv = [[UIWebView alloc]initWithFrame:CGRectZero];
    return [wv stringByEvaluatingJavaScriptFromString:@"navigator.userAgent"];
}

-(void) startLoadingAnimationWithMessage:(NSString *)message tag:(NSUInteger)tag  _view:(UIView*)_view
{
    //CGFloat activityIndicatorPadding = 15.0f;
    
    // new transparent view to disable user interaction during operation.
    UIView *activityView = [[UIView alloc] initWithFrame: [[UIScreen mainScreen] bounds]];
    activityView.tag = tag;
    activityView.backgroundColor = [UIColor colorWithRed:(242.0/255.0) green:(0.0/255.0) blue:(40.0/255.0) alpha:0.8];
    activityView.alpha = 1.0;
    
    UIImage *coinImg = [UIImage imageNamed:@"bgcoin.png"];
    UIImageView *coinView = [[UIImageView alloc] initWithImage:coinImg];
    coinView.frame = CGRectMake(CGRectGetMidX([activityView bounds])-(172/2),CGRectGetMidY([activityView bounds])-(179/2),344/2,358/2);
    coinView.contentMode = UIViewContentModeScaleAspectFit;
    [activityView addSubview:coinView];
    
    NSMutableArray *characterImageArray = [@[] mutableCopy];
    for (int i = 1; i < 36; i++) {
        //NSLog(@"Attempting to load: loading%d.png",i);
        UIImage *characterImage = [UIImage imageNamed:[NSString stringWithFormat:@"loading%d", i]];
        [characterImageArray addObject:characterImage];
    }
    
    UIImageView *characterImageView =[[UIImageView alloc]
                                     initWithFrame:CGRectMake(CGRectGetMidX([activityView bounds])-45,
                                                              CGRectGetMidY([activityView bounds])-50,
                                                              200/2,
                                                              150/2)];
    //characterImageView.frame = CGRectMake(CGRectGetMidX([activityView bounds])-(172/2),CGRectGetMidY([activityView bounds])-(179/2),400/2,300/2);
    characterImageView.contentMode = UIViewContentModeScaleAspectFit;
    characterImageView.animationImages = characterImageArray;
    characterImageView.animationRepeatCount = 100;
    characterImageView.animationDuration = 1.5;
    characterImageView.image = characterImageArray[0];
    [activityView addSubview:characterImageView];
    [characterImageView startAnimating];
    
    UILabel *requestingInformation = [[UILabel alloc] initWithFrame:CGRectMake(0,CGRectGetMaxY([characterImageView bounds])+CGRectGetHeight([characterImageView bounds]) , CGRectGetWidth([activityView bounds]), CGRectGetHeight([activityView bounds]))];
    [requestingInformation setFont:[UIFont fontWithName:@"Avenir-Black" size:18]];
    
    requestingInformation.text = @"Loading App Rewarder..";
    requestingInformation.textAlignment = NSTextAlignmentCenter;
    requestingInformation.textColor = [UIColor colorWithRed:188 green:149 blue:88 alpha:1.0];
    requestingInformation.backgroundColor = [UIColor clearColor];
    [activityView addSubview:requestingInformation];
    
    /*
    
    //Loader spinner
    UIActivityIndicatorView *activityIndicator = [[UIActivityIndicatorView alloc] initWithActivityIndicatorStyle:UIActivityIndicatorViewStyleWhiteLarge];
    [activityView addSubview:activityIndicator];
    
    UILabel *requestingInformation = [[UILabel alloc] init];
    requestingInformation.text = message;
    requestingInformation.backgroundColor = [UIColor clearColor];
    [activityView addSubview:requestingInformation];
    
    CGSize requestingInformationSize = [requestingInformation.text sizeWithFont:requestingInformation.font constrainedToSize:CGSizeMake(9999, 9999) lineBreakMode:requestingInformation.lineBreakMode];
    CGFloat totalWidthOfUIELements = (requestingInformationSize.width + activityIndicator.frame.size.width + activityIndicatorPadding);
    
    CGFloat activityIndicatorStartX = (_view.frame.size.width - totalWidthOfUIELements)/2;
    
    activityIndicator.center = CGPointMake(activityIndicatorStartX,(_view.frame.size.height/2));
    requestingInformation.frame = CGRectMake((activityIndicator.frame.origin.x + activityIndicator.frame.size.width + activityIndicatorPadding), (_view.frame.size.height/2)-10, requestingInformationSize.width, requestingInformation.font.lineHeight);
    */
    [_view addSubview:activityView];
    [_view bringSubviewToFront:activityView];
    
    //[activityIndicator startAnimating];
}

-(void) stopLoadingAnimationWithTag:(NSUInteger)tag  _view:(UIView*)_view
{
    UIView *activityView = [_view viewWithTag:tag];
	/*
    for (View *subview in [activityView subviews]) {
     
        if ([subview isKindOfClass:[UIActivityIndicatorView class]]) {
            
            //UIActivityIndicatorView *activityIndicator = (UIActivityIndicatorView *)subview;
            //[activityIndicator stopAnimating];
            break;
         
        }
    }*/
    
	[activityView removeFromSuperview];
}

- (BOOL)application:(UIApplication *)application
            openURL:(NSURL *)url
  sourceApplication:(NSString *)sourceApplication
         annotation:(id)annotation
{

    
    //- (BOOL)application:(UIApplication *)application handleOpenURL:(NSURL *)url {
    //NSLog(@"!!!: %@", [url relativePath]);
    //NSLog(@"query string: %@", [url query]);
    //NSLog(@"host: %@", [url host]);
    //NSLog(@"url path: %@", [url path]);
    NSString *isRef;
    isRef =[defaults valueForKey:@"isReferred"]; //see if the user has initiated referral already. if not, lets capture it
    if(![isRef isEqualToString:@"1"] && [[url host]  isEqual:@"referral"])
    {
        //NSLog(@"REFERRAL");
        referrerID = [[url path] substringWithRange:NSMakeRange(1, [[url path] length]-1)];
        referrerSource = [url query];
        
        [defaults setValue:referrerID forKey:@"referrerID"];
        [defaults setValue:referrerSource forKey:@"referrerSource"];
    
        NSLog(@"referrerID: %@",referrerID);
        NSLog(@"referrerSource: %@",referrerSource);
        [Flurry logEvent:[NSString stringWithFormat:@"referral_%@",referrerSource ]]; //successful referral
        
        
    } else if([[url host]  isEqual:@"referral"]) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"Referrals"
                                                        message:[NSString stringWithFormat:@"You've already claimed referral code %@. Please email support@apprewarder if you're experiencing any difficulties.", [defaults valueForKey:@"referrerID"]]
                                                       delegate:self
                                              cancelButtonTitle:@"Alright"
                                              otherButtonTitles:nil];
        [alert show];
        [Flurry logEvent:@"referral_0"]; //failed referral
        
        
        
    }
    //[MobileAppTracker applicationDidOpenURL:[url absoluteString] sourceApplication:sourceApplication];
    [FBSession.activeSession handleOpenURL:url];
    return YES;
}


- (void)applicationDidBecomeActive:(UIApplication *)application {
    //[MobileAppTracker measureSession];
    
    if(didRegisterFacebook && ![self isFacebooklogin])
    {
        [FBSession.activeSession openWithCompletionHandler:^(FBSession *session, FBSessionState status, NSError *error) {
        }];
    } else {
        [FBAppCall handleDidBecomeActive];
    }
    
    if(!didRegisterFacebook && [self isFacebooklogin]) {
        [defaults setValue:@"1" forKey:@"didRegisterFacebook"];
        [self setFbToken:[[[FBSession activeSession] accessTokenData] accessToken]];
        didRegisterFacebook = YES;
        //[bridge send:@"alert('hai');"];
    }
    

    
    if(inBackground && ![self isRegistered] )
    {
        [self loadCredentials];
        NSLog(@"Waking and reloading launch URL with referral: %@",referrerID);
        //NSURLRequest *requestObj = [NSURLRequest requestWithURL:[NSURL URLWithString: serverRoot]];
        
        NSURLRequest *requestObj = [NSURLRequest requestWithURL:[NSURL URLWithString: [NSString stringWithFormat:@"%@?arReferralID=%@&arReferralSource=%@",serverRoot,(referrerID)?referrerID:@"",(referrerSource)?referrerSource:@""]]];

        [_webView loadRequest:requestObj];
        inBackground = NO;
    }
    
    else if(inBackground)
    {
        NSLog(@"Waking up from slumber and reloading url");
        NSURLRequest *requestObj = [NSURLRequest requestWithURL:[NSURL URLWithString: [NSString stringWithFormat:@"%@r",serverRoot]]];
        [_webView loadRequest:requestObj];
        inBackground = NO;
    }
    
    
}


- (void)applicationDidEnterBackground:(UIApplication *)application {
    NSLog(@"We were sent to the background");
    
    inBackground = YES;
}


- (BOOL)application:(UIApplication *)application didFinishLaunchingWithOptions:(NSDictionary *)launchOptions {
    
    
    // Account Configuration info - must be set
/*
    [MobileAppTracker initializeWithMATAdvertiserId:@"18998"
                                   MATConversionKey:@"02e98ae8a0579bfc328c8d6e42ac2ac7"];
*/  
    // Used to pass us the IFA, enabling highly accurate 1-to-1 attribution.
    // Required for many advertising networks.
    /*
    [MobileAppTracker setAppleAdvertisingIdentifier:[[ASIdentifierManager sharedManager] advertisingIdentifier]
                         advertisingTrackingEnabled:[[ASIdentifierManager sharedManager] isAdvertisingTrackingEnabled]];
    */
    //setenv("CFNETWORK_DIAGNOSTICS", "3", 1);
    [Parse setApplicationId:@"uK1iER515MIcn9YrrtbdLVotQSj7Z9F6Csntu9zm"
                  clientKey:@"s4S2sXUCVHJjQHdcrvjiOjteeRFgB4wck19SqbeY"];
    [application registerForRemoteNotificationTypes:UIRemoteNotificationTypeBadge|
     UIRemoteNotificationTypeAlert|
     UIRemoteNotificationTypeSound];
    
    [UIApplication sharedApplication].applicationIconBadgeNumber = 0;
    
    if (application.applicationState != UIApplicationStateBackground) {
        // Track an app open here if we launch with a push, unless
        // "content_available" was used to trigger a background push (introduced
        // in iOS 7). In that case, we skip tracking here to avoid double
        // counting the app-open.
        BOOL preBackgroundPush = ![application respondsToSelector:@selector(backgroundRefreshStatus)];
        BOOL oldPushHandlerOnly = ![self respondsToSelector:@selector(application:didReceiveRemoteNotification:fetchCompletionHandler:)];
        BOOL noPushPayload = ![launchOptions objectForKey:UIApplicationLaunchOptionsRemoteNotificationKey];
        if (preBackgroundPush || oldPushHandlerOnly || noPushPayload) {
            [PFAnalytics trackAppOpenedWithLaunchOptions:launchOptions];
        }
    }
    
    
    [Flurry setCrashReportingEnabled:YES];
    [Flurry startSession:@"NPD2BPXJ3R44PCWZ6JSY"];
    
    
     NSLog (@"%s", __PRETTY_FUNCTION__);
    
    APLViewController * viewController = [[APLViewController alloc] init];
    self.window.rootViewController = viewController;
     
    
    //self.window = [[UIWindow alloc] initWithFrame:[[UIScreen mainScreen] bounds]];
    [self.window makeKeyAndVisible];
    defaults = [NSUserDefaults standardUserDefaults];
    [self loadCredentials];
    
    //[self startLoadingAnimationWithMessage:@"Loading.." tag:1234 _view:self.window];
    //return YES;
    

    userAgent = self.getUserAgent;
    NSLog(@"userAgent: %@",self.getUserAgent);
    //Lets register and setup the user agent
    
    NSString *ua = [NSString stringWithFormat:@"App Rewarder Client/%@ (Apple iOS; cpu %@ %@ like mac os x)",
                           clientVersion,
                           [self device_model],
                           [self device_version]];
    
    NSDictionary *dictionary = @{ @"UserAgent" : ua}; [[NSUserDefaults standardUserDefaults] registerDefaults:dictionary];
    
    
    //determine mode and set where the server is pointing to
    void (^selectedCase)() = @{
                               @"LOCAL" : ^{
                                   
                                   //if(LOCAL_IP) serverRoot = [NSString stringWithFormat:@"http://%@/",LOCAL_IP];
                                   //else serverRoot = @"http://m.apprewarder/";
                                   serverRoot = @"http://m.apprewarder/";
                                   
                                   
                               },
                               @"STAGE" : ^{
                                   serverRoot = @"https://mstage.apprewarder.com/";
                                   
                               },
                               @"PROD" : ^{
                                   serverRoot = @"https://m.apprewarder.com/";
                               }
                               }[MODE];
    
    if (selectedCase != nil)
        selectedCase();
    
    serverURL = serverRoot;
    
    

     
        if(![self isConnected]) {
        UIAlertView *alert = [[UIAlertView alloc] initWithTitle:@"No Internet Connection Available"
                                                        message:@"Unable to connect to App Rewarder's servers. Please check your connectivity and try again later."
                                                       delegate:self
                                              cancelButtonTitle:@"Try Again"
                                              otherButtonTitles:nil];
        [alert show];
        
        
    } else {
        
        
        
        
        
        _webView = [[UIWebView alloc] initWithFrame:self.window.bounds];
       
        
        
        
        bridge = [WebViewJavascriptBridge bridgeForWebView:_webView webViewDelegate:self handler:^(id data, WVJBResponseCallback responseCallback) {
            NSLog(@"ObjC received message from JS: %@", data);
            
            NSError *e = nil;
            
            NSMutableArray *jsonArray = [NSJSONSerialization JSONObjectWithData: [data dataUsingEncoding:NSUTF8StringEncoding] options: NSJSONReadingMutableContainers error: &e];
            
            NSString *f = [jsonArray valueForKey:@"f" ];
            NSLog(@"Function request: %@",f);
            
            void (^selectedCase)() = @{
                                       @"set_uid": ^{
                                           if(userID == nil)
                                           {
                                               NSLog(@"Got set_uid call!");
                                               
                                               NSString *uid = [jsonArray valueForKey:@"uid"];
                                               NSLog(@"Received request to set userID: %@",uid);
                                               [self setUserID:uid];
                                               [self loadCredentials];
                                              // responseCallback([NSString stringWithFormat:@"{\"user_agent\":\"%@\"}",userAgent]);
                                           }
                                       },
                                       @"device_imei" : ^{
                                           responseCallback([self device_imei]);
                                       },
                                       @"device_mac" :^{
                                           responseCallback([self device_mac]);
                                       },
                                       @"device_version" : ^{
                                           responseCallback([self device_version]);
                                           
                                       },
                                       @"device_model" : ^{
                                           responseCallback([self device_model]);
                                       },
                                       @"device_id" : ^{
                                           NSLog(@"device_id requested");
                                           responseCallback([self device_id]);
                                       },
                                       @"is_registered":^ {
                                           NSLog(@"is_registered state requested");
                                           responseCallback(([self isRegistered])?@"1":@"0");
                                       },
                                       /*
                                       @"referrer": ^{
                                           NSLog(@"referrer requested");
                                           //responseCallback(referrerID);
                                           if(!isReferred){
                                               responseCallback([NSString stringWithFormat:@"{\"referrer_id\":\"%@\",\"referrer_source\":\"%@\"}",
                                                                 referrerID,
                                                                 referrerSource]);
                                               [defaults setValue:@"1" forKey:@"isReferred"];
                                               [self loadCredentials];
                                               
                                               NSLog(@"server requested referral data, sent. setting isReferred flag to 1");
                                           } else {
                                               responseCallback(@"");
                                           }
                                       },*/
                                       
                                       @"client_login":^ {
                                           serverURL = [NSString stringWithFormat:@"%@register/login/?clientVersion=%@&aidid=%@&aiuid=%@&ua=%@",serverRoot,clientVersion,deviceID,userID,[userAgent stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding]];
                                           NSLog(@"is_registered sending user to %@",serverURL);
                                           
                                           NSURL *url = [NSURL URLWithString:serverURL];
                                           NSURLRequest *requestObj = [NSURLRequest requestWithURL:url];
                                           [_webView loadRequest:requestObj];
                                       },
                                       @"register_device": ^{
                                           //send device information in json format back to the server
                                           //to complete the registration process
                                           [self setDeviceID:[self device_id]];
                                           responseCallback([NSString stringWithFormat:@"{\"deviceIMEI\":\"%@\",\"deviceVersion\":\"%@\",\"deviceModel\":\"%@\",\"deviceID\":\"%@\",\"deviceMAC\":\"%@\",\"clientVersion\":\"%@\",\"userAgent\":\"%@\"}",[self device_imei],[self device_version],[self device_model],[self device_id],[self device_mac],clientVersion,[userAgent stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding]]);
                                           [Flurry logEvent:@"register_device"];
                                       },
                                       @"is_jailbroken": ^{
                                           responseCallback(([self isJailbroken])?@"1":@"0");
                                       },
                                       @"exit": ^{
                                           responseCallback(@"1");
                                           [Flurry logEvent:@"server_exit"];
                                           exit(0);
                                       },
                                       @"fb_login": ^ {
                                           NSLog(@"Facebook login called!");
                                           // If the session state is any of the two "open" states when the button is clicked
                                           if ([self isFacebooklogin]) {
                                               [FBSession.activeSession closeAndClearTokenInformation];
                                           } else {
                                               [FBSession openActiveSessionWithReadPermissions:@[@"public_profile,email,user_friends,publish_actions,user_likes"]
                                                                                  allowLoginUI:YES
                                                                             completionHandler:
                                                ^(FBSession *session, FBSessionState state, NSError *error) {
                                                    [self sessionStateChanged:session state:state error:error];
                                                }];
                                           }
                                       },
                                       @"fb_data": ^{
                                           
                                           if ([self isFacebooklogin]) {
                                               NSString *response =[NSString stringWithFormat:
                                                                    @"{\"fbEmail\":\"%@\",\"fbName\":\"%@\",\"fbPhoto\":\"%@\",\"fbUserID\":\"%@\",\"fbLocale\":\"%@\",\"fbGender\":\"%@\",\"fbVerified\":\"%@\",\"fbToken\":\"%@\"}",
                                                                    fbEmail,fbName,fbPhoto,fbUserID,fbLocale,fbGender,fbVerified,fbToken];
                                               NSLog(@"Session open: %@",response);
                                               responseCallback(response);
                                           } else {
                                               //the user never logged in via facebook or they logged out
                                               NSLog(@"Session not open!");
                                               responseCallback(@"0");
                                           }
                                       }
                                       }[f];
            if (selectedCase != nil)
                selectedCase();
        }];
        
        NSLog(@"Bridge initialized!");

        
        if([self isRegistered]) {
            NSString *_url = serverURL;
            serverURL = [NSString stringWithFormat:@"%@register/login/?clientVersion=%@&aidid=%@&aiuid=%@&ua=%@",_url,clientVersion,deviceID,userID,[userAgent stringByAddingPercentEscapesUsingEncoding:NSUTF8StringEncoding]];
            
        } else
        {
            //if the user is not registered
            NSLog(@"Not registered, registering user");
            serverURL = serverRoot;
        }
        if(!didInitialLaunch)
        {
            //call server, if the user was referred there should be browser-side cookies. lets have the server
            //pull these cookies and relaunch the app via URL scheme calling 'referral' in the scheme!
            //this is a one time only check, so now that we've checked, lets set this to true
            [defaults setValue:@"1" forKey:@"didInitialLaunch"];
            [self loadCredentials];
            [[UIApplication sharedApplication] openURL:[NSURL URLWithString:[NSString stringWithFormat:@"%@i/ios",serverRoot]]];
        }
        
        NSLog(@"Opening URL: %@",serverURL);
        
        
        NSURL *url = [NSURL URLWithString:serverURL];
        NSURLRequest *requestObj = [NSURLRequest requestWithURL:url];
        _webView.scalesPageToFit = YES;
  
         [self.window addSubview:_webView];
        [_webView loadRequest:requestObj];
        NSLog(@"Loading: %@",serverURL);
        NSLog(@"Referral state: %d",(int)isReferred);
        NSLog(@"Registered state: %d",[self isRegistered]);
    }

    return YES;
}


- (void)application:(UIApplication *)application didRegisterForRemoteNotificationsWithDeviceToken:(NSData *)deviceToken {
    PFInstallation *currentInstallation = [PFInstallation currentInstallation];
    [currentInstallation setDeviceTokenFromData:deviceToken];
    currentInstallation.channels = @[@"global"];
    [currentInstallation saveInBackground];
}

- (void)application:(UIApplication *)application didFailToRegisterForRemoteNotificationsWithError:(NSError *)error {
    if (error.code == 3010) {
        NSLog(@"Push notifications are not supported in the iOS Simulator.");
    } else {
        // show some alert or otherwise handle the failure to register.
        NSLog(@"application:didFailToRegisterForRemoteNotificationsWithError: %@", error);
    }
}

- (void)application:(UIApplication *)application didReceiveRemoteNotification:(NSDictionary *)userInfo {
    [PFPush handlePush:userInfo];
    if (application.applicationState == UIApplicationStateInactive) {
        // The application was just brought from the background to the foreground,
        // so we consider the app as having been "opened by a push notification."
        [PFAnalytics trackAppOpenedWithRemoteNotificationPayload:userInfo];
    }
}



// This method will handle ALL the session state changes in the app
- (void)sessionStateChanged:(FBSession *)session state:(FBSessionState) state error:(NSError *)error
{
    
    // If the session was opened successfully
    if (!error && state == FBSessionStateOpen){
        
        //any time a new session is started with the user, lets update their facebook information locally
        [FBRequestConnection startWithGraphPath:@"/me" parameters:nil HTTPMethod:@"GET" completionHandler:^(FBRequestConnection *connection, id result, NSError *error) {
            if([result valueForKey:@"email"]) [self setFbEmail:[result valueForKey:@"email"]];
            if([result valueForKey:@"name"]) [self setFbName:[result valueForKey:@"name"]];
            if([result valueForKey:@"photo"]) [self setFbPhoto:[result valueForKey:@"photo"]];
            if([result valueForKey:@"id"]) [self setFbUserID:[result valueForKey:@"id"]];
            if([result valueForKey:@"locale"]) [self setFbLocale:[result valueForKey:@"locale"]];
            if([result valueForKey:@"gender"]) [self setFbGender:[result valueForKey:@"gender"]];
            if([result valueForKey:@"verified"]) [self setFbVerified:[result valueForKey:@"verified"]];
        }];
        
        
        
        
        NSLog(@"Session opened");
    }
    if (state == FBSessionStateClosed || state == FBSessionStateClosedLoginFailed){
        // If the session is closed
        NSLog(@"Session closed");
        // Show the user the logged-out UI
        //[self userLoggedOut];
    }
    
    // Handle errors
    if (error){
        NSLog(@"Error");
        //NSString *alertText;
        // If the error requires people using an app to make an action outside of the app in order to recover
        if ([FBErrorUtility shouldNotifyUserForError:error] == YES){
            NSLog(@"Something went wrong");
            
        } else {
            
            // If the user cancelled login, do nothing
            if ([FBErrorUtility errorCategoryForError:error] == FBErrorCategoryUserCancelled) {
                NSLog(@"User cancelled login");
                
                // Handle session closures that happen outside of the app
            } else if ([FBErrorUtility errorCategoryForError:error] == FBErrorCategoryAuthenticationReopenSession){
                NSLog(@"Your current session is no longer valid. Please log in again.");
                
                
                // For simplicity, here we just show a generic message for all other errors
                // You can learn how to handle other errors using our guide: https://developers.facebook.com/docs/ios/errors
            } else {
                //Get more error information from the error
                //NSDictionary *errorInformation = [[[error.userInfo objectForKey:@"com.facebook.sdk:ParsedJSONResponseKey"] objectForKey:@"body"] objectForKey:@"error"];
                
                // Show the user an error message
                //alertText = [NSString stringWithFormat:@"Please retry. \n\n If the problem persists contact us and mention this error code: %@", [errorInformation objectForKey:@"message"]];
                NSLog(@"Please retry. \n\n If the problem persists contact us and mention this error code");
            }
        }
        // Clear this token
        [FBSession.activeSession closeAndClearTokenInformation];
        // Show the user the logged-out UI
        //.[self userLoggedOut];
    }
}




-(BOOL)isConnected {
    
    Reachability* reachability = [Reachability reachabilityWithHostName:@"mstage.apprewarder.com"];
    NetworkStatus remoteHostStatus = [reachability currentReachabilityStatus];
    
    if(remoteHostStatus == NotReachable)
    {
        NSLog(@"Could not connect to the servers");
        
        return false;
    }
    else if (remoteHostStatus == ReachableViaWWAN)
    {
        NSLog(@"Able to conenct via WWAN");
        
        return true;
    }
    else if (remoteHostStatus == ReachableViaWiFi)
    {
        NSLog(@"Able to connect via WiFi");
        
        return true;
        
    }
    return false;
}

-(BOOL)isJailbroken {
    //check file path for Cydia
    
    NSString *filePath = @"/Applications/Cydia.app";
    
    if ([[NSFileManager defaultManager] fileExistsAtPath:filePath]){
        NSLog(@"/Application/Cydia.app detected");
        [Flurry logEvent:@"isJailBroken_1"];
        return true;
    }
    
    //check cydia URL schema
    NSURL* url = [NSURL URLWithString:@"cydia://package/com.example.package"];
    if( [[UIApplication sharedApplication] canOpenURL:url]) {
        NSLog(@"URL scheme cydia://package/com.example.package detected!");
        [Flurry logEvent:@"isJailBroken_2"];
        return true;
    }
    
    //check if we can access this file outside sandbox
    NSFileManager * fileManager = [NSFileManager defaultManager];
    if( [fileManager fileExistsAtPath:@"/private/var/lib/apt/"]) {
        NSLog(@"/private/var/lib/apt/ detected");
        [Flurry logEvent:@"isJailBroken_3"];
        return true;
    }
    
    return false;
}

- (NSString *) device_idfa
{
    NSString *advertiser_id;
    advertiser_id = [[[ASIdentifierManager sharedManager] advertisingIdentifier] UUIDString];
    NSLog(@"Advertiser ID: %@",advertiser_id);
    return advertiser_id;
}

- (NSString *) device_imei
{
    NSUUID *UUID;
    NSString *strUUID;
    UUID =[[UIDevice currentDevice] identifierForVendor];
    strUUID = [UUID UUIDString];
    
    return strUUID;
}


- (NSString *) device_version
{
    return [[UIDevice currentDevice] systemVersion];
}

- (NSString *) device_model
{
    return [self device_name];//[[UIDevice currentDevice] systemName];//[NSString stringWithFormat:@"%@,%@",[[UIDevice currentDevice] model],[[UIDevice currentDevice] systemName]];
}

- (NSString *)device_id
{
    if([MODE  isEqual: @"LOCAL"])
    {    return @"A3C4A48A-ED4A-436D-8A01-886ED8269210";
        
        
    }
    else {
        return [self device_idfa];
        //return [[NSUUID UUID] UUIDString];
        
    }
    
}

-(NSString*) device_name
{
    struct utsname systemInfo;
    uname(&systemInfo);
    
    return [NSString stringWithCString:systemInfo.machine
                              encoding:NSUTF8StringEncoding];
}


- (NSString *)device_mac
{
    int                 mgmtInfoBase[6];
    char                *msgBuffer = NULL;
    NSString            *errorFlag = NULL;
    size_t              length;
    
    // Setup the management Information Base (mib)
    mgmtInfoBase[0] = CTL_NET;        // Request network subsystem
    mgmtInfoBase[1] = AF_ROUTE;       // Routing table info
    mgmtInfoBase[2] = 0;
    mgmtInfoBase[3] = AF_LINK;        // Request link layer information
    mgmtInfoBase[4] = NET_RT_IFLIST;  // Request all configured interfaces
    
    // With all configured interfaces requested, get handle index
    if ((mgmtInfoBase[5] = if_nametoindex("en0")) == 0)
        errorFlag = @"if_nametoindex failure";
    // Get the size of the data available (store in len)
    else if (sysctl(mgmtInfoBase, 6, NULL, &length, NULL, 0) < 0)
        errorFlag = @"sysctl mgmtInfoBase failure";
    // Alloc memory based on above call
    else if ((msgBuffer = malloc(length)) == NULL)
        errorFlag = @"buffer allocation failure";
    // Get system information, store in buffer
    else if (sysctl(mgmtInfoBase, 6, msgBuffer, &length, NULL, 0) < 0)
    {
        free(msgBuffer);
        errorFlag = @"sysctl msgBuffer failure";
    }
    else
    {
        // Map msgbuffer to interface message structure
        struct if_msghdr *interfaceMsgStruct = (struct if_msghdr *) msgBuffer;
        
        // Map to link-level socket structure
        struct sockaddr_dl *socketStruct = (struct sockaddr_dl *) (interfaceMsgStruct + 1);
        
        // Copy link layer address data in socket structure to an array
        unsigned char macAddress[6];
        memcpy(&macAddress, socketStruct->sdl_data + socketStruct->sdl_nlen, 6);
        
        // Read from char array into a string object, into traditional Mac address format
        NSString *macAddressString = [NSString stringWithFormat:@"%02X:%02X:%02X:%02X:%02X:%02X",
                                      macAddress[0], macAddress[1], macAddress[2], macAddress[3], macAddress[4], macAddress[5]];
        NSLog(@"Mac Address: %@", macAddressString);
        
        // Release the buffer memory
        free(msgBuffer);
        
        return macAddressString;
    }
    
    // Error...
    NSLog(@"Error: %@", errorFlag);
    
    return nil;
}

@end


