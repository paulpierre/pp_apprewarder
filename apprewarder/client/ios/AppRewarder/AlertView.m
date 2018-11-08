#import "AlertView.h"

@interface AlertView () <UIAlertViewDelegate>

@end

@implementation AlertView

- (id)initWithTitle:(NSString *)title message:(NSString *)message cancelButtonTitle:(NSString *)cancelButtonTitle otherButtonTitles:(NSArray *)otherButtonTitles {
    
    self = [self initWithTitle:title message:message delegate:self cancelButtonTitle:cancelButtonTitle otherButtonTitles:nil];
    
    if (self) {
        for (NSString *buttonTitle in otherButtonTitles) {
            [self addButtonWithTitle:buttonTitle];
        }
    }
    return self;
}
@end