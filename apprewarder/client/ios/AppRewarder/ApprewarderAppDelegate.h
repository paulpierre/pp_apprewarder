#import <UIKit/UIKit.h>
#import "WebViewJavascriptBridge.h"

@interface ApprewarderAppDelegate : UIResponder <UIApplicationDelegate, UIWebViewDelegate>

@property (strong, nonatomic) UIWindow *window;
@property (strong, nonatomic) WebViewJavascriptBridge *javascriptBridge;

- (BOOL)isJailbroken;

@end
