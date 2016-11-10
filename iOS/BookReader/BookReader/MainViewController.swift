//
//  ViewController.swift
//  BookReader
//
//  Created by Arthur on 16/9/1.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit
import SnapKit
import DrawerController
import RTRootNavigationController


class MainViewController: UIViewController {
    
    //var visibleViewController:UIViewController?;
    var drawerController: DrawerController!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        view.backgroundColor = UIColor.redColor();
        
        //隐藏导航栏
        self.navigationController?.navigationBarHidden = true
        
        let centerViewController = CenterViewController();
        let leftMenuViewController = LeftMenuViewController();
        let rightMenuViewController = RightMenuViewController();
        
        let centerNavigationController = UINavigationController(rootViewController: centerViewController);
        
        // 初始化侧滑菜单.
        self.drawerController = DrawerController(centerViewController:centerNavigationController    //中间的用户书架
            , leftDrawerViewController: leftMenuViewController  //左侧菜单
            , rightDrawerViewController: rightMenuViewController)   //右侧菜单
        
        self.drawerController.showsShadows = false
        
        self.drawerController.restorationIdentifier = "Drawer"
        self.drawerController.maximumRightDrawerWidth = 200.0
        self.drawerController.openDrawerGestureModeMask = .All
        self.drawerController.closeDrawerGestureModeMask = .All
        self.drawerController.maximumLeftDrawerWidth = 60;
        self.drawerController.shouldStretchDrawer = false
        self.drawerController.drawerVisualStateBlock = { (drawerController, drawerSide, percentVisible) in
            let block = DrawerVisualStateManager.sharedManager.drawerVisualStateBlockForDrawerSide(drawerSide)
            block?(drawerController, drawerSide, percentVisible)
        }
        view.addSubview(drawerController.view)
        drawerController.view.snp_makeConstraints {[unowned self] (make) in
            make.edges.equalTo(self.view)
        }
        
        self.addObserver()
        
        //更新约束
        view.setNeedsUpdateConstraints();
    }
    
    //开始更新约束
    override func updateViewConstraints() {
        
        super.updateViewConstraints();
    }
    
    
    
//    func showViewController(viewControler: UIViewController, animated: Bool){
//        if(animated){
//            
//        }
//        else{
//            viewControler.view.alpha = 0;//先隐藏
//            self.view.bringSubviewToFront(viewControler.view);//置顶
//            //做一个渐隐切换动画
//            UIView.animateWithDuration(kAnimationSpringToNormal, animations: {[unowned self] in
//                self.visibleViewController!.view.alpha = 0;
//                viewControler.view.alpha = 1;
//            })
//        }
//    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    override func viewDidDisappear(animated: Bool) {
        super.viewDidDisappear(animated);
        
    }
    
    override func preferredStatusBarStyle()->UIStatusBarStyle{
        return UIStatusBarStyle.LightContent
    }
    
    //添加监听
    func addObserver(){
        NSNotificationCenter.defaultCenter().addObserver(self, selector:#selector(MainViewController.showViewController(_:)), name: kShowCategoryViewController, object: nil)
    }
    
    
    func showViewController(notification:NSNotification){
        if(notification.name == kShowCategoryViewController){
            var url:NSURL = NSURL(string:"http://10.1.1.169/bookreader/Server/think/public/api/category/app")!
            
            if guard title = notification.object{
                url =
            }
            let categoryViewController = CategoryViewController.init(open: url, title: (notification.object ?? "分类") as! String)
            self.navigationController?.pushViewController(categoryViewController, animated: true)
        }
    }
}

