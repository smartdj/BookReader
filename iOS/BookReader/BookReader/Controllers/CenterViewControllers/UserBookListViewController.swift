//
//  BookListViewController.swift
//  BookReader
//
//  Created by Arthur on 16/9/1.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit

class UserBookListViewController: UIViewController, UITableViewDelegate, UITableViewDataSource {
    
    let dataSource:NSMutableArray = NSMutableArray()
    let tableView:UITableView = UITableView()
    let refreshControl:UIRefreshControl = UIRefreshControl()
    
    func setupTableView()
    {
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
        tableView.snp_makeConstraints(closure: {[unowned self] (make) -> Void in
            make.edges.equalTo(self.view)
            })
    }
    
    func setupRefreshControl(){
        //添加刷新
        refreshControl.addTarget(self, action: #selector(UserBookListViewController.refreshData),
                                 forControlEvents: UIControlEvents.ValueChanged)
        refreshControl.attributedTitle = NSAttributedString(string: "下拉刷新数据")
        self.tableView.addSubview(refreshControl)
    }
    
    override func viewDidLoad() {
        setupTableView()
        setupRefreshControl()
        refreshData()
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
    
    // 刷新数据
    func refreshData() {
        UserBookWebAPI.refresh {[unowned self] (statusCode, dataModels) -> (Void) in
            self.dataSource.removeAllObjects()
            self.tableView.reloadData()
            self.refreshControl.endRefreshing()
        }
    }
}
