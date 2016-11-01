//
//  Log.swift
//  BookReader
//
//  Created by Arthur on 2016/11/1.
//  Copyright © 2016年 Arthur. All rights reserved.
//

import UIKit
import XCGLogger


// Create a logger object with no destinations
let log = XCGLogger(identifier: "advancedLogger", includeDefaultDestinations: false)

//配置Log
func setupLog(){
    // Create a destination for the system console log (via NSLog)
    let systemLogDestination:XCGNSLogDestination = XCGNSLogDestination(owner: log, identifier: "advancedLogger.systemLogDestination")
    
    // Optionally set some configuration options
    systemLogDestination.outputLogLevel = .Debug
    systemLogDestination.showLogIdentifier = false
    systemLogDestination.showFunctionName = true
    systemLogDestination.showThreadName = true
    systemLogDestination.showLogLevel = true
    systemLogDestination.showFileName = true
    systemLogDestination.showLineNumber = true
    systemLogDestination.showDate = true
    
    // Add the destination to the logger
    log.addLogDestination(systemLogDestination)
    
    // Create a file log destination
    let fileLogDestination:XCGFileLogDestination = XCGFileLogDestination(owner: log, writeToFile: String(format: "%s/%s", DFileHelper.documentDirectoryPath, "log/log.txt"), identifier: "advancedLogger.fileLogDestination")
    
    // Optionally set some configuration options
    fileLogDestination.outputLogLevel = .Debug
    fileLogDestination.showLogIdentifier = false
    fileLogDestination.showFunctionName = true
    fileLogDestination.showThreadName = true
    fileLogDestination.showLogLevel = true
    fileLogDestination.showFileName = true
    fileLogDestination.showLineNumber = true
    fileLogDestination.showDate = true
    
    // Process this destination in the background
    fileLogDestination.logQueue = XCGLogger.logQueue
    
    // Add the destination to the logger
    log.addLogDestination(fileLogDestination)
    
    // Add basic app info, version info etc, to the start of the logs
    log.logAppDetails()
}

