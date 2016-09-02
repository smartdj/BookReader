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
    
    let userBookListViewController = UserBookListViewController();

    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        view.backgroundColor = UIColor.redColor();
        title = "盗版小说！"
        
        self.setupLeftMenuButton()
        self.setupRightMenuButton()
        
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

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }


}

