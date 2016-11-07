//
//  CategoryViewController.swift
//  BookReader
//
//  Created by Arthur on 2016/11/4.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit
import WebKit
import MBProgressHUD

//分类界面
class CategoryViewController: UIViewController, WKNavigationDelegate {

    let webView:WKWebView = WKWebView();
    var URL:NSURL? = nil
    var webPageTitle:String? = nil
    var bridge:ZHWebViewBridge? = nil
    
    convenience init(open:NSURL, title:String){
        self.init()
        self.title = title
        self.URL = open
    }
    
    override func viewDidLoad() {
        super.viewDidLoad()
        self.setupNavigationBar()
        view.backgroundColor = UIColor.whiteColor()
        // Do any additional setup after loading the view.
        
        
        if let URL = URL{
            
            view.addSubview(webView)
            webView.snp_makeConstraints {[unowned self] (make) in
                make.edges.equalTo(self.view)
            }
            
            let req = NSURLRequest(URL:URL)
            self.webView.navigationDelegate = self
            self.webView.loadRequest(req)
            
            MBProgressHUD.showHUDAddedTo(self.view, animated: true)
        }
        
        if let title = webPageTitle{
            self.title = title
        }
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    func webView(webView: WKWebView, didFinishNavigation navigation: WKNavigation!){
        MBProgressHUD.hideHUDForView(self.view, animated: true)
    }

    //修改导航栏后退按钮（参考RTRootNavigationController）
    override func customBackItemWithTarget(target: AnyObject!, action: Selector) -> UIBarButtonItem! {
        let goBackButton:UIButton = UIButton()
        let normalImage = UIImage(named: "nav_back_white")
        let selectedImage = UIImage(named: "nav_back_white_selected")
        
        goBackButton.setImage(normalImage, forState: UIControlState.Normal)
        goBackButton.setImage(selectedImage, forState: UIControlState.Highlighted)
        goBackButton.frame = CGRectMake(0, 0, 85, 44)
        goBackButton.addTarget(target, action: action, forControlEvents: UIControlEvents.TouchUpInside)
        
        let goBackBarButtonItem = UIBarButtonItem.init(customView: goBackButton)
        
        return goBackBarButtonItem
    }
    
    //设置导航栏
    func setupNavigationBar(){
        //设置导航栏背景色
        self.navigationController!.navigationBar.barTintColor = UIColor.colorFromRBG(0xa70a0b)
        
        //设置导航栏标题颜色
        self.navigationController!.navigationBar.titleTextAttributes = [NSForegroundColorAttributeName: UIColor.whiteColor()]
    }
}
