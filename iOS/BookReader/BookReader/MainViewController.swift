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


class MainViewController: UIViewController {
    
    var visibleViewController:UIViewController?;
    
    lazy var centerViewController:CenterViewController = {
        var centerViewController = CenterViewController()
        self.addChildViewController(centerViewController)
        self.view.addSubview(centerViewController.view)
        return centerViewController;
    }()
    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        view.backgroundColor = UIColor.redColor();
        title = "盗版小说！"
        
        self.setupLeftMenuButton()
        self.setupRightMenuButton()
        
        centerViewController.view.snp_makeConstraints {[unowned self] (make) in
            make.edges.equalTo(self.view)
        }
        
        
        //更新约束
        view.setNeedsUpdateConstraints();
    }
    
    //开始更新约束
    override func updateViewConstraints() {
        
        super.updateViewConstraints();
    }
    
    func setupLeftMenuButton() {
        let leftDrawerButton = DrawerBarButtonItem(target: self, action: #selector(leftDrawerButtonPress(_:)))
        self.navigationItem.setLeftBarButtonItem(leftDrawerButton, animated: true)
    }
    
    func setupRightMenuButton() {
        let rightDrawerButton = DrawerBarButtonItem(target: self, action: #selector(rightDrawerButtonPress(_:)))
        self.navigationItem.setRightBarButtonItem(rightDrawerButton, animated: true)
    }
    
    func leftDrawerButtonPress(sender: AnyObject?) {
        self.evo_drawerController?.toggleDrawerSide(.Left, animated: true, completion: nil)
    }
    
    func rightDrawerButtonPress(sender: AnyObject?) {
        self.evo_drawerController?.toggleDrawerSide(.Right, animated: true, completion: nil)
    }
    
    func showViewController(viewControler: UIViewController, animated: Bool){
        if(animated){
            
        }
        else{
            viewControler.view.alpha = 0;//先隐藏
            self.view.bringSubviewToFront(viewControler.view);//置顶
            //做一个渐隐切换动画
            UIView.animateWithDuration(kAnimationSpringToNormal, animations: {[unowned self] in
                self.visibleViewController!.view.alpha = 0;
                viewControler.view.alpha = 1;
            })
        }
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }

    override func viewDidDisappear(animated: Bool) {
        super.viewDidDisappear(animated);
        
    }
}

