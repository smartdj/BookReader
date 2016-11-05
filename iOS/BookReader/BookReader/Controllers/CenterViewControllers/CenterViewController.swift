//
//  CenterViewController.swift
//  BookReader
//
//  Created by Arthur on 2016/10/28.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit
import DrawerController

class CenterViewController: UIViewController {

    lazy var bookListViewController:UserBookListViewController = {
        var bookListViewController:UserBookListViewController = UserBookListViewController()
        
        return bookListViewController
    }()
    
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        //centerNavigationController.edgesForExtendedLayout = UIRectEdgeNone //指示了下层 view 扩展 layout 的方向，默认值是 UIRectEdgeAll，就是会向各个方向扩展（其实在这里能扩展的方向也只有上方）
        //centerNavigationController.extendedLayoutIncludesOpaqueBars = true //在不透明导航栏下， view 仍然会在全屏幕布局
        
        self.title = "盗版小说"

        self.setupStatusBar()
        self.setupNavigationBar()
        self.setupLeftMenuButton()
        self.setupRightMenuButton()
        
        // Do any additional setup after loading the view.
        self .addChildViewController(bookListViewController);
        view.addSubview(bookListViewController.view);
        
        bookListViewController.view.snp_makeConstraints { [unowned self] (make) -> Void in
            make.edges.equalTo(self.view);
        };
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }
    
    //设置左侧导航栏
    func setupLeftMenuButton() {
        let leftDrawerButton = UIBarButtonItem(imageName:"nav_home_side_menu", highlightedImageName:"nav_home_side_menu_selected", title: nil, titleColor: nil, fontSize: nil, size: CGSizeMake(49, 44), target:self, action:#selector(leftDrawerButtonPress(_:)))
        
        self.navigationItem.setLeftBarButtonItem(leftDrawerButton, animated: true)
    }
    
    //设置右侧导航栏
    func setupRightMenuButton() {
        let rightDrawerButton = UIBarButtonItem(imageName:"nav_add_book", highlightedImageName:"nav_add_book_selected", title: nil, titleColor: nil, fontSize: nil, size: CGSizeMake(49, 44), target:self, action:#selector(rightDrawerButtonPress(_:)))
        
        self.navigationItem.setRightBarButtonItem(rightDrawerButton, animated: true)
    }
    
    //左侧导航栏按钮按下
    func leftDrawerButtonPress(sender: AnyObject?) {
        self.evo_drawerController?.toggleDrawerSide(.Left, animated: true, completion: nil)
    }
    
    //右侧导航栏按钮按下
    func rightDrawerButtonPress(sender: AnyObject?) {
        self.evo_drawerController?.toggleDrawerSide(.Right, animated: true, completion: nil)
    }
    
    //设置导航栏
    func setupNavigationBar(){
        //设置导航栏背景色
        self.navigationController!.navigationBar.barTintColor = UIColor.colorFromRBG(0xa70a0b)
        
        //设置导航栏标题颜色
        self.navigationController!.navigationBar.titleTextAttributes = [NSForegroundColorAttributeName: UIColor.whiteColor()]
        
        //设置导航栏下方的边框
        self.navigationController?.navigationBar.setBackgroundImage(UIImage(named: ""), forBarMetrics: UIBarMetrics.Default)
        self.navigationController?.navigationBar.shadowImage = UIImage(named: "")
    }
    
    func setupStatusBar(){
        UIApplication.sharedApplication().statusBarStyle = UIStatusBarStyle.LightContent
    }
}
