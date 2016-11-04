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

class CategoryViewController: UIViewController, WKNavigationDelegate {

    let webView:WKWebView = WKWebView();
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        view.backgroundColor = UIColor.whiteColor()
        // Do any additional setup after loading the view.
        view.addSubview(webView)
        webView.snp_makeConstraints {[unowned self] (make) in
            make.edges.equalTo(self.view)
        }
        
        let url = NSURL(string:"http://10.1.1.169/bookreader/Server/think/public/api/category/app")
        let req = NSURLRequest(URL:url!)
        self.webView.navigationDelegate = self
        self.webView.loadRequest(req)
        
        MBProgressHUD.showHUDAddedTo(self.view, animated: true)
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    

    func webView(webView: WKWebView, didFinishNavigation navigation: WKNavigation!){
        log.debug("did finished")
        MBProgressHUD.hideHUDForView(self.view, animated: true)
    }

}
