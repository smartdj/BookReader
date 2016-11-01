//
//  CenterViewController.swift
//  BookReader
//
//  Created by Arthur on 2016/10/28.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit

class CenterViewController: UIViewController {

    lazy var bookListViewController:UserBookListViewController = {
        var bookListViewController:UserBookListViewController = UserBookListViewController()
        
        return bookListViewController
    }()
    
    
    override func viewDidLoad() {
        super.viewDidLoad()

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
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destinationViewController.
        // Pass the selected object to the new view controller.
    }
    */

}
