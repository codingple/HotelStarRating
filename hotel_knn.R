# import data
dir <- "hotel_data.txt"
data <- read.table(dir, header=TRUE)
tmp_data <- data
tmp_data <- tmp_data[,-1]


# Training
training_num <- as.integer( nrow(data) * 0.8 )

tr_data <- data.frame()
te_data <- data.frame()

tr_data <- rbind(tr_data, tmp_data[sample(nrow(tmp_data), training_num),])
te_data <- rbind(te_data, tmp_data[tmp_data %in% tr_data == FALSE,])

cl <- tr_data[,3]

# KNN
result <- knn(tr_data[,-3], te_data[,-3], cl)
tru <- te_data[,3]

accu_length <- length(which(result==tru))
accu <- accu_length/length(result)
cat(accu)
cat('\n')


# Graph
rang = c(0,1200)
test <- factor(tru, levels=c(1,2,3,4,5))
final <- test[test == result]
plot(test, ylim=rang, border='red', col='transparent')
par(new=T)
plot(final, ylim=rang)
