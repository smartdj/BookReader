//
//  BookListViewController.swift
//  BookReader
//
//  Created by Arthur on 16/9/1.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit

class UserBookListViewController: UIViewController, UITableViewDelegate, UITableViewDataSource {
    
    var dataSource:NSMutableArray = NSMutableArray()
    
    lazy var tableView:UITableView = {
        var tableView:UITableView = UITableView()
        //注册Cell
        tableView.registerClass(UserBookListTableViewCell.self, forCellReuseIdentifier: kDefaultCellIdentifier)
        
        //设置数据源和代理
        tableView.delegate = self;
        tableView.dataSource = self;
        //tableView.backgroundColor = UIColor.clearColor();
        //tableView.separatorColor = UIColor.blackColor();//设置分割线颜色
        //tableView.separatorInset = UIEdgeInsetsMake(0, 0, 0, 0);
        tableView.tableFooterView = UIView(frame: CGRectZero);
        self.view.addSubview(tableView)
        return tableView;
    }()
    
    override func viewDidLoad() {
        
    }
    
    func tableView(tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return dataSource.count;
    }
    
    func tableView(tableView: UITableView, cellForRowAtIndexPath indexPath: NSIndexPath) -> UITableViewCell {
        let cell = tableView.dequeueReusableCellWithIdentifier(kDefaultCellIdentifier, forIndexPath: indexPath) as! UserBookListTableViewCell
        
        // 配置cell
        cell.dataModel = self.dataSource[indexPath.row] as? UserBookModel;
        // 返回cell
        return cell
    }
}
