#import <UIKit/UIKit.h>

@interface AlertView : UIAlertView

@property (copy, nonatomic) void (^completion)(BOOL, NSInteger);

- (id)initWithTitle:(NSString *)title message:(NSString *)message cancelButtonTitle:(NSString *)cancelButtonTitle otherButtonTitles:(NSArray *)otherButtonTitles;

@end