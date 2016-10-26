//
//  LeftSideViewController.swift
//  BookReader
//
//  Created by Arthur on 16/9/1.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit
import SnapKit;

class LeftMenuViewController: UIViewController {
    var userButton:UIButton = UIButton();
    var homeButton:UIButton = UIButton();
    var messageButton:UIButton = UIButton();
    var settingsButton:UIButton = UIButton();
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        self.view.backgroundColor = UIColor.blackColor();
        
        userButton.setImage(UIImage.init(named: "hsm_default_avatar"), forState: UIControlState.Normal);
        homeButton.setImage(UIImage.init(named: "hsm_icon_1"), forState: UIControlState.Normal);
        messageButton.setImage(UIImage.init(named: "hsm_icon_2"), forState: UIControlState.Normal);
        settingsButton.setImage(UIImage.init(named: "hsm_icon_3"), forState: UIControlState.Normal);
        
        self.view.addSubview(userButton);
        self.view.addSubview(homeButton);
        self.view.addSubview(messageButton);
        self.view.addSubview(settingsButton);
        
        userButton.snp.makeConstraints { (make) -> Void in
            make.width.equalTo(21)
            make.height.equalTo(23);
            make.center.equalTo(self.view)
        }
    }
}
