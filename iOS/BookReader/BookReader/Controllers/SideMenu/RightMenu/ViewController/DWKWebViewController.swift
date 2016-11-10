//
//  WKWebViewBridge.swift
//  BookReader
//
//  Created by Arthur on 2016/11/8.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit
import WebKit

@objc protocol DWKWebViewControllerDelegate : class{
    optional func reciveJavaScriptCall(data:NSDictionary);
    optional func webView(webView: WKWebView, didFinishNavigation navigation: WKNavigation!)
    optional func webView(webView: WKWebView, didFailNavigation navigation: WKNavigation!, withError error: NSError)
}

public class DWKWebViewController: UIViewController, WKScriptMessageHandler, WKUIDelegate, WKNavigationDelegate {
    
    public var webView: WKWebView!
    weak var delegate:DWKWebViewControllerDelegate?
    
    override public func viewDidLoad() {
        super.viewDidLoad()
        
        let conf = WKWebViewConfiguration()
        conf.userContentController.addScriptMessageHandler(self, name: "OOXX")
        
        self.webView = WKWebView(frame: CGRectMake(0, 0, 10, 10), configuration: conf)
        self.webView.UIDelegate = self
        self.webView.navigationDelegate = self
        self.webView.translatesAutoresizingMaskIntoConstraints = false
        
        self.view.addSubview(self.webView)
        
        self.view.addConstraint(NSLayoutConstraint(item: self.webView, attribute: .Left, relatedBy: .Equal, toItem: self.view, attribute: .Left, multiplier: 1, constant: 0))
        self.view.addConstraint(NSLayoutConstraint(item: self.webView, attribute: .Right, relatedBy: .Equal, toItem: self.view, attribute: .Right, multiplier: 1, constant: 0))
        self.view.addConstraint(NSLayoutConstraint(item: self.webView, attribute: .Top, relatedBy: .Equal, toItem: self.view, attribute: .Top, multiplier: 1, constant: 0))
        self.view.addConstraint(NSLayoutConstraint(item: self.webView, attribute: .Bottom, relatedBy: .Equal, toItem: self.bottomLayoutGuide, attribute: .Bottom, multiplier: 1, constant: 0))
    }
    
    func runPluginJS(names: Array<String>) {
        for name in names {
            if let path = NSBundle.mainBundle().pathForResource(name, ofType: "js", inDirectory: "www/plugins") {
                do {
                    let js = try NSString(contentsOfFile: path, encoding: NSUTF8StringEncoding)
                    self.webView.evaluateJavaScript(js as String, completionHandler: nil)
                } catch let error as NSError {
                    NSLog(error.debugDescription)
                }
            }
        }
    }
}


private typealias wkScriptMessageHandler = DWKWebViewController
extension wkScriptMessageHandler {
    //接收js发送的请求
    public func userContentController(userContentController: WKUserContentController, didReceiveScriptMessage message: WKScriptMessage) {
        if message.name == "OOXX" {
            if let dic = message.body as? NSDictionary {
                delegate?.reciveJavaScriptCall?(dic)
            }
        }
    }
}

