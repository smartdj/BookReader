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
}
