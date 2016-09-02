//
//  ViewController.swift
//  BookReader
//
//  Created by Arthur on 16/9/1.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit
import SnapKit
import LGHelper


class MainViewController: UIViewController {
    
    let userBookListViewController = UserBookListViewController();

    
    override func viewDidLoad() {
        super.viewDidLoad()
        // Do any additional setup after loading the view, typically from a nib.
        view.backgroundColor = UIColor.redColor();
        title = "盗版小说！"
        
        //更新约束
        view.setNeedsUpdateConstraints();
        
    }
    
    //开始更新约束
    override func updateViewConstraints() {
        
        super.updateViewConstraints();
    }

    override func didReceiveMemoryWarning() {
        super.didReceiveMemoryWarning()
        // Dispose of any resources that can be recreated.
    }


}

